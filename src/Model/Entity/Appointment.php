<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Appointment Entity
 *
 * @property int $id
 * @property string $patient_name
 * @property string $patient_phone
 * @property string|null $patient_email
 * @property int|null $service_id
 * @property int|null $doctor_id
 * @property \Cake\I18n\DateTime $appointment_date
 * @property string $status
 * @property string|null $notes
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\Service $service
 * @property \App\Model\Entity\Staff $doctor
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
        'patient_name' => true,
        'patient_phone' => true,
        'patient_email' => true,
        'service_id' => true,
        'doctor_id' => true,
        'appointment_date' => true,
        'status' => true,
        'notes' => true,
        'created' => true,
        'modified' => true,
        'service' => true,
        'doctor' => true,
    ];
}
