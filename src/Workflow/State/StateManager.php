<?php
declare(strict_types=1);

namespace App\Workflow\State;

use Exception;
use JsonPath\JsonObject;

/**
 * Manages workflow state with JSONPath support
 *
 * Provides immutable state updates and JSONPath-based queries
 */
class StateManager
{
    /**
     * Current state data
     *
     * @var array
     */
    private array $state;

    /**
     * Constructor
     *
     * @param array $initialState Initial state data
     */
    public function __construct(array $initialState = [])
    {
        $this->state = $initialState;
    }

    /**
     * Get value by JSONPath or simple key
     *
     * @param string $path JSONPath expression or simple key
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public function get(string $path, mixed $default = null): mixed
    {
        // Handle simple key access
        if (!str_starts_with($path, '$')) {
            return $this->state[$path] ?? $default;
        }

        try {
            $jsonObject = new JsonObject($this->state);
            $result = $jsonObject->get($path);

            if ($result === false) {
                return $default;
            }

            return $result;
        } catch (Exception $e) {
            return $default;
        }
    }

    /**
     * Set value by key
     *
     * @param string $key State key
     * @param mixed $value Value to set
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->state[$key] = $value;
    }

    /**
     * Update state with new data
     *
     * @param array $updates State updates to merge
     * @return void
     */
    public function update(array $updates): void
    {
        $this->state = array_merge($this->state, $updates);
    }

    /**
     * Get all state data
     *
     * @return array
     */
    public function all(): array
    {
        return $this->state;
    }

    /**
     * Check if a key exists
     *
     * @param string $key State key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->state[$key]);
    }

    /**
     * Remove a key from state
     *
     * @param string $key State key
     * @return void
     */
    public function remove(string $key): void
    {
        unset($this->state[$key]);
    }

    /**
     * Create a snapshot of current state
     *
     * @return array
     */
    public function snapshot(): array
    {
        return $this->state;
    }

    /**
     * Restore state from snapshot
     *
     * @param array $snapshot State snapshot
     * @return void
     */
    public function restore(array $snapshot): void
    {
        $this->state = $snapshot;
    }

    /**
     * Evaluate an expression against the state
     *
     * @param string $expression Expression to evaluate
     * @return bool
     */
    public function evaluate(string $expression): bool
    {
        // Simple implementation for now, can be enhanced with expression parser
        if (preg_match('/^state\.(\w+)\s*([<>=!]+)\s*(.+)$/', $expression, $matches)) {
            $key = $matches[1];
            $operator = $matches[2];
            $value = trim($matches[3], '"\'');

            $stateValue = $this->get($key);

            return match ($operator) {
                '==' => $stateValue == $value,
                '===' => $stateValue === $value,
                '!=' => $stateValue != $value,
                '>' => $stateValue > $value,
                '<' => $stateValue < $value,
                '>=' => $stateValue >= $value,
                '<=' => $stateValue <= $value,
                default => false,
            };
        }

        return false;
    }
}
