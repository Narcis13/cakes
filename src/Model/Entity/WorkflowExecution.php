<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\I18n\DateTime;
use Cake\ORM\Entity;

/**
 * WorkflowExecution Entity
 *
 * @property int $id
 * @property int $workflow_id
 * @property string $status
 * @property string|null $current_node
 * @property string|null $current_position
 * @property string $state_json
 * @property string|null $input_data
 * @property string|null $output_data
 * @property string|null $error_message
 * @property int $started_by
 * @property \Cake\I18n\DateTime $started_at
 * @property \Cake\I18n\DateTime|null $paused_at
 * @property \Cake\I18n\DateTime|null $completed_at
 * @property int|null $execution_time
 *
 * @property \App\Model\Entity\Workflow $workflow
 * @property \App\Model\Entity\User $started_by_user
 * @property \App\Model\Entity\WorkflowExecutionLog[] $workflow_execution_logs
 * @property \App\Model\Entity\WorkflowHumanTask[] $workflow_human_tasks
 * @property-read array $state
 * @property-read array $input
 * @property-read array $output
 * @property-read array $position
 * @property-read bool $is_running
 * @property-read bool $is_paused
 * @property-read bool $is_completed
 * @property-read bool $is_failed
 */
class WorkflowExecution extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'workflow_id' => true,
        'status' => true,
        'current_node' => true,
        'current_position' => true,
        'state_json' => true,
        'input_data' => true,
        'output_data' => true,
        'error_message' => true,
        'started_by' => true,
        'started_at' => true,
        'paused_at' => true,
        'completed_at' => true,
        'execution_time' => true,
        'workflow' => true,
    ];

    /**
     * List of computed properties
     *
     * @var array<string>
     */
    protected array $_virtual = ['state', 'input', 'output', 'position', 'is_running', 'is_paused', 'is_completed', 'is_failed'];

    /**
     * Get the parsed state
     *
     * @return array
     */
    protected function _getState(): array
    {
        if (!empty($this->state_json)) {
            return json_decode($this->state_json, true) ?? [];
        }

        return [];
    }

    /**
     * Get the parsed input data
     *
     * @return array
     */
    protected function _getInput(): array
    {
        if (!empty($this->input_data)) {
            return json_decode($this->input_data, true) ?? [];
        }

        return [];
    }

    /**
     * Get the parsed output data
     *
     * @return array
     */
    protected function _getOutput(): array
    {
        if (!empty($this->output_data)) {
            return json_decode($this->output_data, true) ?? [];
        }

        return [];
    }

    /**
     * Get the parsed position
     *
     * @return array
     */
    protected function _getPosition(): array
    {
        if (!empty($this->current_position)) {
            return json_decode($this->current_position, true) ?? [];
        }

        return [];
    }

    /**
     * Check if execution is running
     *
     * @return bool
     */
    protected function _getIsRunning(): bool
    {
        return $this->status === 'running';
    }

    /**
     * Check if execution is paused
     *
     * @return bool
     */
    protected function _getIsPaused(): bool
    {
        return $this->status === 'paused';
    }

    /**
     * Check if execution is completed
     *
     * @return bool
     */
    protected function _getIsCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if execution is failed
     *
     * @return bool
     */
    protected function _getIsFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Update the state
     *
     * @param array $state New state data
     * @return void
     */
    public function updateState(array $state): void
    {
        $this->state_json = json_encode($state);
    }

    /**
     * Merge state updates
     *
     * @param array $updates State updates to merge
     * @return void
     */
    public function mergeState(array $updates): void
    {
        $currentState = $this->state;
        $newState = array_merge($currentState, $updates);
        $this->updateState($newState);
    }

    /**
     * Pause the execution
     *
     * @return void
     */
    public function pause(): void
    {
        $this->status = 'paused';
        $this->paused_at = DateTime::now();
    }

    /**
     * Resume the execution
     *
     * @return void
     */
    public function resume(): void
    {
        $this->status = 'running';
        $this->paused_at = null;
    }

    /**
     * Complete the execution
     *
     * @param array|null $output Optional output data
     * @return void
     */
    public function complete(?array $output = null): void
    {
        $this->status = 'completed';
        $this->completed_at = DateTime::now();
        if ($output !== null) {
            $this->output_data = json_encode($output);
        }
        $this->calculateExecutionTime();
    }

    /**
     * Fail the execution
     *
     * @param string $errorMessage Error message
     * @return void
     */
    public function fail(string $errorMessage): void
    {
        $this->status = 'failed';
        $this->completed_at = DateTime::now();
        $this->error_message = $errorMessage;
        $this->calculateExecutionTime();
    }

    /**
     * Calculate and set execution time
     *
     * @return void
     */
    protected function calculateExecutionTime(): void
    {
        if ($this->started_at && $this->completed_at) {
            $diff = $this->completed_at->getTimestamp() - $this->started_at->getTimestamp();
            $this->execution_time = $diff * 1000; // Convert to milliseconds
        }
    }
}
