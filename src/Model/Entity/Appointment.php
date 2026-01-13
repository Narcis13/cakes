<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\I18n\DateTime;
use Cake\ORM\Entity;

/**
 * Appointment Entity
 *
 * @property int $id
 * @property int|null $patient_id
 * @property string $patient_name
 * @property string $patient_phone
 * @property string $patient_email
 * @property int $service_id
 * @property int $doctor_id
 * @property \Cake\I18n\Date $appointment_date
 * @property \Cake\I18n\Time $appointment_time
 * @property \Cake\I18n\Time $end_time
 * @property string $status
 * @property string|null $notes
 * @property string|null $confirmation_token
 * @property \Cake\I18n\DateTime|null $confirmed_at
 * @property \Cake\I18n\DateTime|null $cancelled_at
 * @property string|null $cancellation_reason
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\Patient|null $patient
 * @property \App\Model\Entity\Service $service
 * @property \App\Model\Entity\Staff $doctor
 * @property-read \Cake\I18n\DateTime|null $full_date_time
 * @property-read bool $is_confirmed
 * @property-read bool $is_cancelled
 * @property-read bool $is_past
 */
class Appointment extends Entity
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
        'patient_id' => true,
        'patient_name' => true,
        'patient_phone' => true,
        'patient_email' => true,
        'patient_cnp' => true,
        'service_id' => true,
        'doctor_id' => true,
        'appointment_date' => true,
        'appointment_time' => true,
        'end_time' => true,
        'status' => false,
        'notes' => true,
        'confirmation_token' => false,
        'confirmed_at' => false,
        'cancelled_at' => false,
        'cancellation_reason' => true,
        'reminded_24h' => false,
        'reminded_2h' => false,
        'created' => false,
        'modified' => false,
        'patient' => true,
        'service' => true,
        'doctor' => true,
    ];

    /**
     * Get full datetime combining date and time
     *
     * @return \Cake\I18n\DateTime|null
     */
    protected function _getFullDateTime(): ?DateTime
    {
        if ($this->has('appointment_date') && $this->has('appointment_time')) {
            $dateTime = clone $this->appointment_date;

            return $dateTime->setTime(
                (int)$this->appointment_time->format('H'),
                (int)$this->appointment_time->format('i'),
            );
        }

        return null;
    }

    /**
     * Check if appointment is confirmed
     *
     * @return bool
     */
    protected function _getIsConfirmed(): bool
    {
        return $this->status === 'confirmed' && $this->confirmed_at !== null;
    }

    /**
     * Check if appointment is cancelled
     *
     * @return bool
     */
    protected function _getIsCancelled(): bool
    {
        return $this->status === 'cancelled' && $this->cancelled_at !== null;
    }

    /**
     * Check if appointment is in the past
     *
     * @return bool
     */
    protected function _getIsPast(): bool
    {
        if ($this->full_date_time) {
            return $this->full_date_time < new DateTime();
        }

        return false;
    }

    /**
     * Get status badge class for display
     *
     * @return string
     */
    protected function _getStatusBadgeClass(): string
    {
        $classes = [
            'pending' => 'warning',
            'confirmed' => 'success',
            'cancelled' => 'danger',
            'completed' => 'info',
            'no-show' => 'secondary',
        ];

        return $classes[$this->status] ?? 'light';
    }
}
