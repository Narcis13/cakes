<?php
declare(strict_types=1);

namespace App\Workflow\Engine;

use App\Workflow\State\StateManager;

/**
 * Execution context passed to nodes
 *
 * Provides access to state, configuration, and runtime services
 */
class ExecutionContext
{
    /**
     * Constructor
     *
     * @param \App\Workflow\State\StateManager $state The state manager
     * @param array $config Node-specific configuration
     * @param \App\Workflow\Engine\RuntimeContext $runtime Runtime context for advanced operations
     */
    public function __construct(
        private StateManager $state,
        private array $config,
        private RuntimeContext $runtime,
    ) {
    }

    /**
     * Get the state manager
     *
     * @return \App\Workflow\State\StateManager
     */
    public function getState(): StateManager
    {
        return $this->state;
    }

    /**
     * Get node configuration
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Get a specific config value
     *
     * @param string $key Config key
     * @param mixed $default Default value if key not found
     * @return mixed
     */
    public function getConfigValue(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Get the runtime context
     *
     * @return \App\Workflow\Engine\RuntimeContext
     */
    public function getRuntime(): RuntimeContext
    {
        return $this->runtime;
    }
}
