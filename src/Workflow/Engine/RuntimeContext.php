<?php
declare(strict_types=1);

namespace App\Workflow\Engine;

use App\Model\Entity\WorkflowExecution;
use Cake\Event\EventDispatcherTrait;
use Cake\ORM\TableRegistry;
use Exception;

/**
 * Runtime context for workflow execution
 *
 * Provides access to runtime services like events, pausing, and database operations
 */
class RuntimeContext
{
    use EventDispatcherTrait;

    /**
     * Pause tokens
     *
     * @var array<string, array>
     */
    private array $pauseTokens = [];

    /**
     * Constructor
     *
     * @param string $workflowId The workflow ID
     * @param string $executionId The execution ID
     * @param \App\Model\Entity\WorkflowExecution $execution The workflow execution entity
     */
    public function __construct(
        private string $workflowId,
        private string $executionId,
        private WorkflowExecution $execution,
    ) {
    }

    /**
     * Get workflow ID
     *
     * @return string
     */
    public function getWorkflowId(): string
    {
        return $this->workflowId;
    }

    /**
     * Get execution ID
     *
     * @return string
     */
    public function getExecutionId(): string
    {
        return $this->executionId;
    }

    /**
     * Get the execution entity
     *
     * @return \App\Model\Entity\WorkflowExecution
     */
    public function getExecution(): WorkflowExecution
    {
        return $this->execution;
    }

    /**
     * Emit a workflow event
     *
     * @param array $event Event data
     * @return void
     */
    public function emit(array $event): void
    {
        $eventName = 'Workflow.' . $event['type'];
        $eventData = array_merge($event, [
            'workflowId' => $this->workflowId,
            'executionId' => $this->executionId,
        ]);

        $this->dispatchEvent($eventName, $eventData);
    }

    /**
     * Pause the workflow execution
     *
     * @return string Pause token
     */
    public function pause(): string
    {
        $token = bin2hex(random_bytes(16));
        $this->pauseTokens[$token] = [
            'created_at' => time(),
            'node' => $this->execution->current_node,
        ];

        // Update execution status
        $this->execution->pause();
        TableRegistry::getTableLocator()->get('WorkflowExecutions')->save($this->execution);

        $this->emit([
            'type' => 'execution_paused',
            'pauseToken' => $token,
        ]);

        return $token;
    }

    /**
     * Wait for resume signal
     *
     * @param string $token Pause token
     * @return array Resume data
     * @throws \Exception When workflow is not paused or token is invalid
     */
    public function waitForResume(string $token): array
    {
        if (!isset($this->pauseTokens[$token])) {
            throw new Exception('Invalid pause token');
        }

        // In a real implementation, this would block or poll for resume
        // For now, we'll implement a simple check
        $humanTasksTable = TableRegistry::getTableLocator()->get('WorkflowHumanTasks');

        $task = $humanTasksTable->find()
            ->where([
                'execution_id' => $this->execution->id,
                'status' => 'completed',
            ])
            ->orderAsc('completed_at')
            ->first();

        if (!$task) {
            throw new Exception('No completed human task found');
        }

        $responseData = json_decode($task->response_data_json, true) ?? [];

        // Clean up token
        unset($this->pauseTokens[$token]);

        // Resume execution
        $this->execution->resume();
        TableRegistry::getTableLocator()->get('WorkflowExecutions')->save($this->execution);

        $this->emit([
            'type' => 'execution_resumed',
            'resumeData' => $responseData,
        ]);

        return $responseData;
    }

    /**
     * Log an execution event
     *
     * @param string $nodeName Node name
     * @param string $level Log level
     * @param string $message Log message
     * @param array|null $data Additional data
     * @return void
     */
    public function log(string $nodeName, string $level, string $message, ?array $data = null): void
    {
        $logsTable = TableRegistry::getTableLocator()->get('WorkflowExecutionLogs');

        $log = $logsTable->newEntity([
            'execution_id' => $this->execution->id,
            'node_name' => $nodeName,
            'level' => $level,
            'message' => $message,
            'data_json' => $data ? json_encode($data) : null,
        ]);

        $logsTable->save($log);
    }

    /**
     * Create a human task
     *
     * @param array $taskData Task data
     * @return \App\Model\Entity\WorkflowHumanTask
     */
    public function createHumanTask(array $taskData): object
    {
        $tasksTable = TableRegistry::getTableLocator()->get('WorkflowHumanTasks');

        $task = $tasksTable->newEntity(array_merge([
            'execution_id' => $this->execution->id,
            'status' => 'pending',
        ], $taskData));

        if ($tasksTable->save($task)) {
            $this->emit([
                'type' => 'human_task_created',
                'taskId' => $task->id,
                'taskData' => $taskData,
            ]);
        }

        return $task;
    }
}
