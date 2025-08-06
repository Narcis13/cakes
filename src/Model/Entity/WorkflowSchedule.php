<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\I18n\DateTime;
use Cake\ORM\Entity;
use DateTimeZone;

/**
 * WorkflowSchedule Entity
 *
 * @property int $id
 * @property int $workflow_id
 * @property string $name
 * @property string|null $description
 * @property string|null $cron_expression
 * @property string $schedule_type
 * @property int|null $interval_minutes
 * @property \Cake\I18n\DateTime|null $run_at
 * @property string|null $input_data_json
 * @property string $timezone
 * @property bool $is_active
 * @property \Cake\I18n\DateTime|null $last_run_at
 * @property int|null $last_execution_id
 * @property \Cake\I18n\DateTime|null $next_run_at
 * @property int $run_count
 * @property int|null $max_runs
 * @property int $created_by
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\Workflow $workflow
 * @property \App\Model\Entity\WorkflowExecution $last_execution
 * @property \App\Model\Entity\User $creator
 * @property-read array|null $input_data
 * @property-read bool $is_due
 * @property-read bool $is_cron
 * @property-read bool $is_interval
 * @property-read bool $is_once
 * @property-read bool $has_reached_max_runs
 * @property-read string $schedule_description
 */
class WorkflowSchedule extends Entity
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
        'name' => true,
        'description' => true,
        'cron_expression' => true,
        'schedule_type' => true,
        'interval_minutes' => true,
        'run_at' => true,
        'input_data_json' => true,
        'timezone' => true,
        'is_active' => true,
        'last_run_at' => true,
        'last_execution_id' => true,
        'next_run_at' => true,
        'run_count' => true,
        'max_runs' => true,
        'created_by' => true,
        'created' => true,
        'modified' => true,
        'workflow' => true,
        'last_execution' => true,
    ];

    /**
     * List of computed properties
     *
     * @var array<string>
     */
    protected array $_virtual = ['input_data', 'is_due', 'is_cron', 'is_interval', 'is_once', 'has_reached_max_runs', 'schedule_description'];

    /**
     * Get the parsed input data
     *
     * @return array|null
     */
    protected function _getInputData(): ?array
    {
        if (!empty($this->input_data_json)) {
            return json_decode($this->input_data_json, true);
        }

        return null;
    }

    /**
     * Check if schedule is due
     *
     * @return bool
     */
    protected function _getIsDue(): bool
    {
        if (!$this->is_active || $this->has_reached_max_runs) {
            return false;
        }

        if ($this->next_run_at) {
            return $this->next_run_at->isPast() || $this->next_run_at->isToday();
        }

        return false;
    }

    /**
     * Check if schedule is cron-based
     *
     * @return bool
     */
    protected function _getIsCron(): bool
    {
        return $this->schedule_type === 'cron';
    }

    /**
     * Check if schedule is interval-based
     *
     * @return bool
     */
    protected function _getIsInterval(): bool
    {
        return $this->schedule_type === 'interval';
    }

    /**
     * Check if schedule is one-time
     *
     * @return bool
     */
    protected function _getIsOnce(): bool
    {
        return $this->schedule_type === 'once';
    }

    /**
     * Check if schedule has reached max runs
     *
     * @return bool
     */
    protected function _getHasReachedMaxRuns(): bool
    {
        return $this->max_runs !== null && $this->run_count >= $this->max_runs;
    }

    /**
     * Get schedule description
     *
     * @return string
     */
    protected function _getScheduleDescription(): string
    {
        switch ($this->schedule_type) {
            case 'cron':
                return 'Cron: ' . $this->cron_expression;
            case 'interval':
                if ($this->interval_minutes < 60) {
                    return 'Every ' . $this->interval_minutes . ' minutes';
                } elseif ($this->interval_minutes < 1440) {
                    return 'Every ' . round($this->interval_minutes / 60, 1) . ' hours';
                } else {
                    return 'Every ' . round($this->interval_minutes / 1440, 1) . ' days';
                }
            case 'once':
                return 'Once at ' . ($this->run_at ? $this->run_at->format('Y-m-d H:i:s') : 'unspecified time');
            default:
                return 'Unknown schedule type';
        }
    }

    /**
     * Set input data from array
     *
     * @param array $data Input data array
     * @return void
     */
    public function setInputData(array $data): void
    {
        $this->input_data_json = json_encode($data);
    }

    /**
     * Calculate next run time
     *
     * @return \Cake\I18n\DateTime|null
     */
    public function calculateNextRunTime(): ?DateTime
    {
        if (!$this->is_active || $this->has_reached_max_runs) {
            return null;
        }

        $now = new DateTime('now', new DateTimeZone($this->timezone));

        switch ($this->schedule_type) {
            case 'cron':
                // TODO: Implement cron expression parser
                return null;

            case 'interval':
                if ($this->last_run_at) {
                    return $this->last_run_at->addMinutes($this->interval_minutes);
                } else {
                    return $now;
                }

            case 'once':
                if (!$this->last_run_at && $this->run_at) {
                    return $this->run_at;
                }

                return null;

            default:
                return null;
        }
    }

    /**
     * Mark as executed
     *
     * @param int $executionId Execution ID
     * @return void
     */
    public function markAsExecuted(int $executionId): void
    {
        $this->last_run_at = new DateTime();
        $this->last_execution_id = $executionId;
        $this->run_count = $this->run_count + 1;
        $this->next_run_at = $this->calculateNextRunTime();

        if ($this->has_reached_max_runs) {
            $this->is_active = false;
        }
    }

    /**
     * Activate schedule
     *
     * @return void
     */
    public function activate(): void
    {
        $this->is_active = true;
        if (!$this->next_run_at) {
            $this->next_run_at = $this->calculateNextRunTime();
        }
    }

    /**
     * Deactivate schedule
     *
     * @return void
     */
    public function deactivate(): void
    {
        $this->is_active = false;
    }
}
