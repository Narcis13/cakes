<?php
declare(strict_types=1);

namespace App\Workflow\Node;

use App\Workflow\Node\Control\BranchNode;
use App\Workflow\Node\Control\ForEachNode;
use App\Workflow\Node\Control\WhileConditionNode;
use App\Workflow\Node\Utility\LogNode;
use App\Workflow\Node\Utility\SetFlagNode;
use App\Workflow\Node\Utility\WaitNode;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Exception;

/**
 * Registry for workflow nodes
 *
 * Manages node instances and provides lazy loading
 */
class NodeRegistry
{
    /**
     * Loaded node instances
     *
     * @var array<string, \App\Workflow\Node\NodeInterface>
     */
    private array $nodes = [];

    /**
     * Node metadata cache
     *
     * @var array<string, array>
     */
    private array $metadata = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->loadBuiltinNodes();
        $this->loadDatabaseNodes();
    }

    /**
     * Get a node instance
     *
     * @param string $name Node name
     * @return \App\Workflow\Node\NodeInterface
     * @throws \Exception When node not found
     */
    public function get(string $name): NodeInterface
    {
        if (!isset($this->nodes[$name])) {
            throw new Exception("Node '$name' not found in registry");
        }

        return $this->nodes[$name];
    }

    /**
     * Check if node exists
     *
     * @param string $name Node name
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->nodes[$name]);
    }

    /**
     * Register a node
     *
     * @param string $name Node name
     * @param \App\Workflow\Node\NodeInterface $node Node instance
     * @return void
     */
    public function register(string $name, NodeInterface $node): void
    {
        $this->nodes[$name] = $node;
        $this->metadata[$name] = $node->getMetadata();
    }

    /**
     * Get all registered nodes
     *
     * @return array<string, \App\Workflow\Node\NodeInterface>
     */
    public function all(): array
    {
        return $this->nodes;
    }

    /**
     * Get all node metadata
     *
     * @return array<string, array>
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * Get nodes by type
     *
     * @param string $type Node type
     * @return array<string, \App\Workflow\Node\NodeInterface>
     */
    public function getByType(string $type): array
    {
        $filtered = [];

        foreach ($this->nodes as $name => $node) {
            $metadata = $node->getMetadata();
            if (($metadata['type'] ?? '') === $type) {
                $filtered[$name] = $node;
            }
        }

        return $filtered;
    }

    /**
     * Get nodes by category
     *
     * @param string $category Node category
     * @return array<string, \App\Workflow\Node\NodeInterface>
     */
    public function getByCategory(string $category): array
    {
        $filtered = [];

        foreach ($this->nodes as $name => $node) {
            $metadata = $node->getMetadata();
            if (($metadata['category'] ?? '') === $category) {
                $filtered[$name] = $node;
            }
        }

        return $filtered;
    }

    /**
     * Load built-in nodes
     *
     * @return void
     */
    private function loadBuiltinNodes(): void
    {
        // Control nodes
        $this->register('whileCondition', new WhileConditionNode());
        $this->register('forEach', new ForEachNode());
        $this->register('branch', new BranchNode());

        // Utility nodes
        $this->register('wait', new WaitNode());
        $this->register('setFlag', new SetFlagNode());
        $this->register('log', new LogNode());
    }

    /**
     * Load nodes from database
     *
     * @return void
     */
    private function loadDatabaseNodes(): void
    {
        try {
            $nodesTable = TableRegistry::getTableLocator()->get('WorkflowNodes');
            $nodes = $nodesTable->find()
                ->where(['is_active' => true])
                ->all();

            foreach ($nodes as $nodeEntity) {
                $handlerClass = $nodeEntity->handler_class;

                if (class_exists($handlerClass)) {
                    $instance = new $handlerClass();

                    if ($instance instanceof NodeInterface) {
                        $this->register($nodeEntity->name, $instance);
                    }
                }
            }
        } catch (Exception $e) {
            // Log error but don't fail - database might not be ready yet
            Log::warning('Failed to load database nodes: ' . $e->getMessage());
        }
    }

    /**
     * Reload nodes from database
     *
     * @return void
     */
    public function reload(): void
    {
        // Keep built-in nodes
        $builtinNodes = [];
        foreach (['whileCondition', 'forEach', 'branch', 'wait', 'setFlag', 'log'] as $name) {
            if (isset($this->nodes[$name])) {
                $builtinNodes[$name] = $this->nodes[$name];
            }
        }

        // Clear and reload
        $this->nodes = $builtinNodes;
        $this->metadata = [];

        foreach ($this->nodes as $name => $node) {
            $this->metadata[$name] = $node->getMetadata();
        }

        $this->loadDatabaseNodes();
    }
}
