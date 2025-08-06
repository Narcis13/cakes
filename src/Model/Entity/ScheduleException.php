<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ScheduleException Entity
 *
 * @property int $id
 * @property int $staff_id
 * @property \Cake\I18n\Date $exception_date
 * @property bool $is_working
 * @property \Cake\I18n\Time|null $start_time
 * @property \Cake\I18n\Time|null $end_time
 * @property string|null $reason
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\Staff $staff
 */
class ScheduleException extends Entity
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
        'staff_id' => true,
        'exception_date' => true,
        'is_working' => true,
        'start_time' => true,
        'end_time' => true,
        'reason' => true,
        'created' => true,
        'modified' => true,
        'staff' => true,
    ];

    /**
     * Get exception type for display
     *
     * @return string
     */
    protected function _getExceptionType(): string
    {
        return $this->is_working ? __('Extra Work Day') : __('Day Off');
    }

    /**
     * Get formatted time range
     *
     * @return string|null
     */
    protected function _getTimeRange(): ?string
    {
        if ($this->is_working && $this->start_time && $this->end_time) {
            return sprintf(
                '%s - %s',
                $this->start_time->format('H:i'),
                $this->end_time->format('H:i'),
            );
        }

        return null;
    }

    /**
     * Get display label for the exception
     *
     * @return string
     */
    protected function _getDisplayLabel(): string
    {
        $label = $this->exception_date->format('Y-m-d') . ' - ' . $this->exception_type;

        if ($this->is_working && $this->time_range) {
            $label .= ' (' . $this->time_range . ')';
        }

        if ($this->reason) {
            $label .= ' - ' . $this->reason;
        }

        return $label;
    }
}
