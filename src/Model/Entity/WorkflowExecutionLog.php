<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * WorkflowExecutionLog Entity
 *
 * @property int $id
 * @property int $execution_id
 * @property string $node_name
 * @property string|null $node_type
 * @property string|null $edge_taken
 * @property string $level
 * @property string|null $message
 * @property string|null $data_json
 * @property string|null $state_snapshot
 * @property int|null $execution_time
 * @property \Cake\I18n\DateTime $created
 *
 * @property \App\Model\Entity\WorkflowExecution $execution
 * @property-read array|null $data
 * @property-read array|null $state
 * @property-read string $execution_time_formatted
 * @property-read bool $is_error
 * @property-read bool $is_warning
 * @property-read bool $is_info
 * @property-read bool $is_debug
 */
class WorkflowExecutionLog extends Entity
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
        'execution_id' => true,
        'node_name' => true,
        'node_type' => true,
        'edge_taken' => true,
        'level' => true,
        'message' => true,
        'data_json' => true,
        'state_snapshot' => true,
        'execution_time' => true,
        'created' => true,
        'execution' => true,
    ];

    /**
     * List of computed properties
     *
     * @var array<string>
     */
    protected array $_virtual = ['data', 'state', 'execution_time_formatted', 'is_error', 'is_warning', 'is_info', 'is_debug'];

    /**
     * Get the parsed data
     *
     * @return array|null
     */
    protected function _getData(): ?array
    {
        if (!empty($this->data_json)) {
            return json_decode($this->data_json, true);
        }

        return null;
    }

    /**
     * Get the parsed state
     *
     * @return array|null
     */
    protected function _getState(): ?array
    {
        if (!empty($this->state_snapshot)) {
            return json_decode($this->state_snapshot, true);
        }

        return null;
    }

    /**
     * Get formatted execution time
     *
     * @return string
     */
    protected function _getExecutionTimeFormatted(): string
    {
        if ($this->execution_time === null) {
            return '-';
        }

        if ($this->execution_time < 1000) {
            return $this->execution_time . 'ms';
        } elseif ($this->execution_time < 60000) {
            return round($this->execution_time / 1000, 2) . 's';
        } else {
            return round($this->execution_time / 60000, 2) . 'm';
        }
    }

    /**
     * Check if log is error level
     *
     * @return bool
     */
    protected function _getIsError(): bool
    {
        return $this->level === 'error';
    }

    /**
     * Check if log is warning level
     *
     * @return bool
     */
    protected function _getIsWarning(): bool
    {
        return $this->level === 'warning';
    }

    /**
     * Check if log is info level
     *
     * @return bool
     */
    protected function _getIsInfo(): bool
    {
        return $this->level === 'info';
    }

    /**
     * Check if log is debug level
     *
     * @return bool
     */
    protected function _getIsDebug(): bool
    {
        return $this->level === 'debug';
    }

    /**
     * Set data from array
     *
     * @param array $data Data array
     * @return void
     */
    public function setData(array $data): void
    {
        $this->data_json = json_encode($data);
    }

    /**
     * Set state from array
     *
     * @param array $state State array
     * @return void
     */
    public function setState(array $state): void
    {
        $this->state_snapshot = json_encode($state);
    }

    /**
     * Get level color class
     *
     * @return string
     */
    public function getLevelColor(): string
    {
        return match($this->level) {
            'error' => 'danger',
            'warning' => 'warning',
            'info' => 'info',
            'debug' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Get level icon
     *
     * @return string
     */
    public function getLevelIcon(): string
    {
        return match($this->level) {
            'error' => 'fa-times-circle',
            'warning' => 'fa-exclamation-triangle',
            'info' => 'fa-info-circle',
            'debug' => 'fa-bug',
            default => 'fa-circle'
        };
    }
}
