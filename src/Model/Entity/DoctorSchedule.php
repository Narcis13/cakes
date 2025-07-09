<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DoctorSchedule Entity
 *
 * @property int $id
 * @property int $staff_id
 * @property int $day_of_week
 * @property \Cake\I18n\Time $start_time
 * @property \Cake\I18n\Time $end_time
 * @property int $service_id
 * @property int $max_appointments
 * @property int|null $slot_duration
 * @property int $buffer_minutes
 * @property bool $is_active
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\Staff $staff
 * @property \App\Model\Entity\Service $service
 */
class DoctorSchedule extends Entity
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
        'day_of_week' => true,
        'start_time' => true,
        'end_time' => true,
        'service_id' => true,
        'max_appointments' => true,
        'slot_duration' => true,
        'buffer_minutes' => true,
        'is_active' => true,
        'created' => true,
        'modified' => true,
        'staff' => true,
        'service' => true,
    ];

    /**
     * Get the day name for display
     *
     * @return string
     */
    protected function _getDayName(): string
    {
        $days = [
            1 => __('Monday'),
            2 => __('Tuesday'),
            3 => __('Wednesday'),
            4 => __('Thursday'),
            5 => __('Friday'),
            6 => __('Saturday'),
            7 => __('Sunday'),
        ];

        return $days[$this->day_of_week] ?? '';
    }

    /**
     * Get formatted schedule time range
     *
     * @return string
     */
    protected function _getTimeRange(): string
    {
        if ($this->start_time && $this->end_time) {
            return sprintf(
                '%s - %s',
                $this->start_time->format('H:i'),
                $this->end_time->format('H:i')
            );
        }

        return '';
    }

    /**
     * Get effective slot duration (from schedule or service)
     *
     * @return int
     */
    protected function _getEffectiveSlotDuration(): int
    {
        if ($this->slot_duration !== null && $this->slot_duration > 0) {
            return $this->slot_duration;
        }

        if ($this->has('service') && $this->service->has('duration_minutes')) {
            return $this->service->duration_minutes;
        }

        return 30; // Default to 30 minutes
    }

    /**
     * Calculate the number of available slots based on schedule duration
     *
     * @return int
     */
    protected function _getTotalSlots(): int
    {
        if (!$this->start_time || !$this->end_time) {
            return 0;
        }

        $startMinutes = $this->start_time->format('H') * 60 + $this->start_time->format('i');
        $endMinutes = $this->end_time->format('H') * 60 + $this->end_time->format('i');
        $totalMinutes = $endMinutes - $startMinutes;

        $slotDuration = $this->effective_slot_duration + $this->buffer_minutes;

        return max(0, (int)floor($totalMinutes / $slotDuration));
    }
}