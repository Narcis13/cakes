<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\I18n\DateTime;
use Cake\ORM\Entity;

/**
 * WorkflowHumanTask Entity
 *
 * @property int $id
 * @property int $execution_id
 * @property string $node_name
 * @property string $title
 * @property string|null $description
 * @property string|null $form_schema_json
 * @property string|null $context_data_json
 * @property int|null $assigned_to
 * @property string|null $assigned_role
 * @property string $priority
 * @property string $status
 * @property string|null $response_data_json
 * @property int|null $completed_by
 * @property \Cake\I18n\DateTime|null $due_at
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $assigned_at
 * @property \Cake\I18n\DateTime|null $completed_at
 *
 * @property \App\Model\Entity\WorkflowExecution $execution
 * @property \App\Model\Entity\User|null $assigned_user
 * @property \App\Model\Entity\User|null $completed_user
 * @property-read array|null $form_schema
 * @property-read array|null $context_data
 * @property-read array|null $response_data
 * @property-read bool $is_pending
 * @property-read bool $is_completed
 * @property-read bool $is_overdue
 * @property-read string|null $time_to_complete
 */
class WorkflowHumanTask extends Entity
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
        'title' => true,
        'description' => true,
        'form_schema_json' => true,
        'context_data_json' => true,
        'assigned_to' => true,
        'assigned_role' => true,
        'priority' => true,
        'status' => true,
        'response_data_json' => true,
        'completed_by' => true,
        'due_at' => true,
        'created' => true,
        'assigned_at' => true,
        'completed_at' => true,
        'execution' => true,
    ];

    /**
     * List of computed properties
     *
     * @var array<string>
     */
    protected array $_virtual = ['form_schema', 'context_data', 'response_data', 'is_pending', 'is_completed', 'is_overdue'];

    /**
     * Get the parsed form schema
     *
     * @return array|null
     */
    protected function _getFormSchema(): ?array
    {
        if (!empty($this->form_schema_json)) {
            return json_decode($this->form_schema_json, true);
        }

        return null;
    }

    /**
     * Get the parsed context data
     *
     * @return array|null
     */
    protected function _getContextData(): ?array
    {
        if (!empty($this->context_data_json)) {
            return json_decode($this->context_data_json, true);
        }

        return null;
    }

    /**
     * Get the parsed response data
     *
     * @return array|null
     */
    protected function _getResponseData(): ?array
    {
        if (!empty($this->response_data_json)) {
            return json_decode($this->response_data_json, true);
        }

        return null;
    }

    /**
     * Check if task is pending
     *
     * @return bool
     */
    protected function _getIsPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if task is completed
     *
     * @return bool
     */
    protected function _getIsCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if task is overdue
     *
     * @return bool
     */
    protected function _getIsOverdue(): bool
    {
        return $this->due_at &&
               $this->due_at->isPast() &&
               !in_array($this->status, ['completed', 'cancelled']);
    }

    /**
     * Get time to complete
     *
     * @return string|null
     */
    protected function _getTimeToComplete(): ?string
    {
        if ($this->assigned_at && $this->completed_at) {
            $diff = $this->completed_at->diff($this->assigned_at);
            $seconds = $diff->s + ($diff->i * 60) + ($diff->h * 3600) + ($diff->days * 86400);

            if ($seconds < 60) {
                return $seconds . 's';
            } elseif ($seconds < 3600) {
                return round($seconds / 60, 1) . 'm';
            } elseif ($seconds < 86400) {
                return round($seconds / 3600, 1) . 'h';
            } else {
                return round($seconds / 86400, 1) . 'd';
            }
        }

        return null;
    }

    /**
     * Set form schema from array
     *
     * @param array $schema Form schema array
     * @return void
     */
    public function setFormSchema(array $schema): void
    {
        $this->form_schema_json = json_encode($schema);
    }

    /**
     * Set context data from array
     *
     * @param array $data Context data array
     * @return void
     */
    public function setContextData(array $data): void
    {
        $this->context_data_json = json_encode($data);
    }

    /**
     * Set response data from array
     *
     * @param array $data Response data array
     * @return void
     */
    public function setResponseData(array $data): void
    {
        $this->response_data_json = json_encode($data);
    }

    /**
     * Complete the task
     *
     * @param int $userId User completing the task
     * @param array $responseData Response data
     * @return void
     */
    public function complete(int $userId, array $responseData): void
    {
        $this->status = 'completed';
        $this->completed_by = $userId;
        $this->completed_at = new DateTime();
        $this->setResponseData($responseData);
    }

    /**
     * Assign the task
     *
     * @param int $userId User to assign to
     * @return void
     */
    public function assign(int $userId): void
    {
        $this->status = 'assigned';
        $this->assigned_to = $userId;
        $this->assigned_at = new DateTime();
    }

    /**
     * Cancel the task
     *
     * @return void
     */
    public function cancel(): void
    {
        $this->status = 'cancelled';
    }

    /**
     * Get priority color class
     *
     * @return string
     */
    public function getPriorityColor(): string
    {
        return match ($this->priority) {
            'urgent' => 'danger',
            'high' => 'warning',
            'medium' => 'info',
            'low' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Get status color class
     *
     * @return string
     */
    public function getStatusColor(): string
    {
        return match ($this->status) {
            'completed' => 'success',
            'in_progress' => 'primary',
            'assigned' => 'info',
            'pending' => 'warning',
            'cancelled' => 'dark',
            'expired' => 'danger',
            default => 'secondary'
        };
    }
}
