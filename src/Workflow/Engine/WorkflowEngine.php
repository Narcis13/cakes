<?php
declare(strict_types=1);

namespace App\Workflow\Engine;

use App\Model\Entity\Workflow;
use App\Model\Entity\WorkflowExecution;
use App\Model\Table\WorkflowExecutionLogsTable;
use App\Model\Table\WorkflowExecutionsTable;
use App\Workflow\Node\NodeRegistry;
use App\Workflow\State\StateManager;
use Cake\I18n\DateTime;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Exception;

/**
 * Main workflow execution engine
 *
 * Implements the FlowScript interpreter for executing workflows
 */
class WorkflowEngine
{
    /**
     * Node registry
     *
     * @var \App\Workflow\Node\NodeRegistry
     */
    private NodeRegistry $nodeRegistry;

    /**
     * Workflow executions table
     *
     * @var \App\Model\Table\WorkflowExecutionsTable
     */
    private WorkflowExecutionsTable $executionsTable;

    /**
     * Workflow execution logs table
     *
     * @var \App\Model\Table\WorkflowExecutionLogsTable
     */
    private WorkflowExecutionLogsTable $logsTable;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->nodeRegistry = new NodeRegistry();
        $this->executionsTable = TableRegistry::getTableLocator()->get('WorkflowExecutions');
        $this->logsTable = TableRegistry::getTableLocator()->get('WorkflowExecutionLogs');
    }

    /**
     * Execute a workflow
     *
     * @param \App\Model\Entity\Workflow $workflow The workflow to execute
     * @param array $inputData Initial input data
     * @param int $userId User starting the execution
     * @return \App\Model\Entity\WorkflowExecution
     * @throws \Exception When workflow execution fails
     */
    public function execute(Workflow $workflow, array $inputData = [], int $userId = 1): WorkflowExecution
    {
        if (!$workflow->is_active) {
            throw new Exception('Cannot execute inactive workflow');
        }

        $definition = $workflow->definition;
        if (!$definition) {
            throw new Exception('Invalid workflow definition');
        }

        // Create execution record
        $execution = $this->createExecution($workflow, $inputData, $userId);

        try {
            // Initialize state
            $initialState = array_merge($definition['initialState'] ?? [], $inputData);
            $stateManager = new StateManager($initialState);

            // Create runtime context
            $runtime = new RuntimeContext(
                (string)$workflow->id,
                (string)$execution->id,
                $execution,
            );

            // Execute workflow nodes
            $result = $this->executeFlow(
                $definition['nodes'] ?? [],
                $stateManager,
                $runtime,
            );

            // Complete execution
            $execution->complete($result['output'] ?? null);
            $execution->state_json = json_encode($stateManager->all());
            $this->executionsTable->save($execution);

            Log::info(sprintf(
                'Workflow execution completed: %s (ID: %s)',
                $workflow->name,
                $execution->id,
            ));
        } catch (Exception $e) {
            // Handle execution failure
            $execution->fail($e->getMessage());
            $this->executionsTable->save($execution);

            Log::error(sprintf(
                'Workflow execution failed: %s (ID: %s) - %s',
                $workflow->name,
                $execution->id,
                $e->getMessage(),
            ));

            throw $e;
        }

        return $execution;
    }

    /**
     * Resume a paused workflow execution
     *
     * @param \App\Model\Entity\WorkflowExecution $execution The execution to resume
     * @param array $resumeData Data from human task completion
     * @return \App\Model\Entity\WorkflowExecution
     * @throws \Exception When resume fails
     */
    public function resume(WorkflowExecution $execution, array $resumeData = []): WorkflowExecution
    {
        if (!$execution->is_paused) {
            throw new Exception('Execution is not paused');
        }

        $workflow = TableRegistry::getTableLocator()->get('Workflows')->get($execution->workflow_id);
        $definition = $workflow->definition;

        // Restore state
        $stateManager = new StateManager($execution->state);
        $stateManager->update($resumeData);

        // Create runtime context
        $runtime = new RuntimeContext(
            (string)$workflow->id,
            (string)$execution->id,
            $execution,
        );

        $execution->resume();
        $this->executionsTable->save($execution);

        try {
            // Continue execution from current position
            $result = $this->executeFlow(
                $definition['nodes'] ?? [],
                $stateManager,
                $runtime,
                $execution->position,
            );

            // Complete execution
            $execution->complete($result['output'] ?? null);
            $execution->state_json = json_encode($stateManager->all());
            $this->executionsTable->save($execution);
        } catch (Exception $e) {
            $execution->fail($e->getMessage());
            $this->executionsTable->save($execution);
            throw $e;
        }

        return $execution;
    }

    /**
     * Execute a flow of nodes
     *
     * @param array $elements Flow elements to execute
     * @param \App\Workflow\State\StateManager $state Current state
     * @param \App\Workflow\Engine\RuntimeContext $runtime Runtime context
     * @param array $startPosition Starting position for resume
     * @return array Execution result
     */
    private function executeFlow(
        array $elements,
        StateManager $state,
        RuntimeContext $runtime,
        array $startPosition = [],
    ): array {
        $nodeIndex = $this->buildNodeIndex($elements);
        $pc = $startPosition['pc'] ?? 0;
        $exitSignal = null;

        while ($pc < count($elements) && !$exitSignal) {
            $element = $elements[$pc];

            // Update current position
            $runtime->getExecution()->current_position = json_encode(['pc' => $pc]);
            $this->executionsTable->save($runtime->getExecution());

            // Branch Structure: [ConditionNode, BranchMap]
            if ($this->isBranchStructure($element)) {
                [$condition, $branchMap] = $element;
                $result = $this->executeNode($condition, $state, $runtime);

                $edgeTaken = array_key_first($result);
                if ($edgeTaken && isset($branchMap[$edgeTaken])) {
                    $branch = $branchMap[$edgeTaken];
                    $branchResult = $this->executeBranch($branch, $state, $runtime);

                    if (isset($branchResult['exit'])) {
                        $exitSignal = $branchResult['exit'];
                    }
                }
            }
            // Loop Structure: [LoopController, NodeSequence]
            elseif ($this->isLoopStructure($element)) {
                [$controller, $sequence] = $element;

                while (true) {
                    $controlResult = $this->executeNode($controller, $state, $runtime);
                    $edge = array_key_first($controlResult);

                    if ($edge === 'exit_loop') {
                        break;
                    } elseif ($edge === 'next_iteration') {
                        // Apply any data from the edge
                        $dataThunk = $controlResult[$edge];
                        if ($dataThunk) {
                            $state->update($dataThunk());
                        }

                        $iterResult = $this->executeFlow($sequence, $state, $runtime);
                        if (isset($iterResult['exit'])) {
                            $exitSignal = $iterResult['exit'];
                            break;
                        }
                    }
                }
            }
            // Simple Node Execution
            else {
                $result = $this->executeNode($element, $state, $runtime);
                $edge = array_key_first($result);

                if ($edge === 'exit') {
                    $exitSignal = 'explicit_exit';
                } elseif ($edge && str_starts_with($edge, 'loopTo:')) {
                    $target = substr($edge, 7);
                    $targetIndex = $nodeIndex[$target] ?? null;
                    if ($targetIndex !== null) {
                        $pc = $targetIndex - 1;
                    }
                }
            }

            $pc++;
        }

        return [
            'completed' => !$exitSignal,
            'exitSignal' => $exitSignal,
            'output' => $state->get('output', []),
        ];
    }

    /**
     * Execute a single node
     *
     * @param array|string $nodeRef Node reference
     * @param \App\Workflow\State\StateManager $state Current state
     * @param \App\Workflow\Engine\RuntimeContext $runtime Runtime context
     * @return array Edge map result
     */
    private function executeNode(
        string|array $nodeRef,
        StateManager $state,
        RuntimeContext $runtime,
    ): array {
        $nodeName = '';
        $config = [];

        if (is_string($nodeRef)) {
            $nodeName = $nodeRef;
        } elseif (is_array($nodeRef)) {
            $nodeName = array_key_first($nodeRef);
            $config = $nodeRef[$nodeName] ?? [];
        }

        // Update current node
        $runtime->getExecution()->current_node = $nodeName;

        try {
            $node = $this->nodeRegistry->get($nodeName);
            $context = new ExecutionContext($state, $config, $runtime);

            $startTime = microtime(true);
            $edges = $node->execute($context);
            $executionTime = (int)((microtime(true) - $startTime) * 1000);

            // Log execution
            $edgeTaken = array_key_first($edges);
            $this->logNodeExecution($runtime, $nodeName, $edgeTaken, $executionTime, $state);

            // Apply edge data if any
            if ($edgeTaken && isset($edges[$edgeTaken])) {
                $dataThunk = $edges[$edgeTaken];
                if (is_callable($dataThunk)) {
                    $data = $dataThunk();
                    if (is_array($data)) {
                        $state->update($data);
                    }
                }
            }

            return $edges;
        } catch (Exception $e) {
            $runtime->log($nodeName, 'error', 'Node execution failed: ' . $e->getMessage());
            throw new Exception("Node '$nodeName' failed: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Execute a branch
     *
     * @param mixed $branch Branch element(s)
     * @param \App\Workflow\State\StateManager $state Current state
     * @param \App\Workflow\Engine\RuntimeContext $runtime Runtime context
     * @return array Branch result
     */
    private function executeBranch(
        mixed $branch,
        StateManager $state,
        RuntimeContext $runtime,
    ): array {
        if ($branch === null) {
            return [];
        }

        if (is_string($branch) || $this->isNodeWithConfig($branch)) {
            $this->executeNode($branch, $state, $runtime);

            return [];
        }

        if (is_array($branch) && !$this->isNodeWithConfig($branch)) {
            return $this->executeFlow($branch, $state, $runtime);
        }

        return [];
    }

    /**
     * Check if element is a branch structure
     *
     * @param mixed $element Element to check
     * @return bool
     */
    private function isBranchStructure(mixed $element): bool
    {
        return is_array($element)
            && count($element) === 2
            && isset($element[1])
            && is_array($element[1])
            && !is_numeric(array_key_first($element[1]));
    }

    /**
     * Check if element is a loop structure
     *
     * @param mixed $element Element to check
     * @return bool
     */
    private function isLoopStructure(mixed $element): bool
    {
        return is_array($element)
            && count($element) === 2
            && isset($element[1])
            && is_array($element[1])
            && is_numeric(array_key_first($element[1]));
    }

    /**
     * Check if element is a node with config
     *
     * @param mixed $element Element to check
     * @return bool
     */
    private function isNodeWithConfig(mixed $element): bool
    {
        return is_array($element)
            && count($element) === 1
            && is_string(array_key_first($element));
    }

    /**
     * Build node index for loopTo support
     *
     * @param array $elements Flow elements
     * @return array<string, int>
     */
    private function buildNodeIndex(array $elements): array
    {
        $index = [];

        foreach ($elements as $i => $element) {
            if (is_string($element)) {
                $index[$element] = $i;
            } elseif ($this->isNodeWithConfig($element)) {
                $nodeName = array_key_first($element);
                $index[$nodeName] = $i;
            }
        }

        return $index;
    }

    /**
     * Create a new execution record
     *
     * @param \App\Model\Entity\Workflow $workflow The workflow
     * @param array $inputData Input data
     * @param int $userId User ID
     * @return \App\Model\Entity\WorkflowExecution
     */
    private function createExecution(Workflow $workflow, array $inputData, int $userId): WorkflowExecution
    {
        $execution = $this->executionsTable->newEntity([
            'workflow_id' => $workflow->id,
            'status' => 'running',
            'state_json' => json_encode($inputData),
            'input_data' => json_encode($inputData),
            'started_by' => $userId,
            'started_at' => DateTime::now(),
        ]);

        if (!$this->executionsTable->save($execution)) {
            throw new Exception('Failed to create workflow execution');
        }

        return $execution;
    }

    /**
     * Log node execution
     *
     * @param \App\Workflow\Engine\RuntimeContext $runtime Runtime context
     * @param string $nodeName Node name
     * @param string|null $edgeTaken Edge taken
     * @param int $executionTime Execution time in ms
     * @param \App\Workflow\State\StateManager $state Current state
     * @return void
     */
    private function logNodeExecution(
        RuntimeContext $runtime,
        string $nodeName,
        ?string $edgeTaken,
        int $executionTime,
        StateManager $state,
    ): void {
        $log = $this->logsTable->newEntity([
            'execution_id' => $runtime->getExecution()->id,
            'node_name' => $nodeName,
            'edge_taken' => $edgeTaken,
            'level' => 'info',
            'message' => sprintf('Node executed: %s -> %s', $nodeName, $edgeTaken ?? 'none'),
            'execution_time' => $executionTime,
            'state_snapshot' => json_encode($state->snapshot()),
        ]);

        $this->logsTable->save($log);
    }
}
