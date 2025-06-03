<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Service Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int|null $department_id
 * @property int|null $duration_minutes
 * @property string|null $price
 * @property string|null $requirements
 * @property bool $is_active
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\Department $department
 * @property \App\Model\Entity\Appointment[] $appointments
 */
class Service extends Entity
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
        'name' => true,
        'description' => true,
        'department_id' => true,
        'duration_minutes' => true,
        'price' => true,
        'requirements' => true,
        'is_active' => true,
        'created' => true,
        'modified' => true,
        'department' => true,
        'appointments' => true,
    ];
}
