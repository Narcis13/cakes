<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Department Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int|null $head_doctor_id
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $floor_location
 * @property string|null $services_html
 * @property string|null $picture
 * @property bool $is_active
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\Service[] $services
 * @property \App\Model\Entity\Staff[] $staff
 */
class Department extends Entity
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
        'head_doctor_id' => true,
        'phone' => true,
        'email' => true,
        'floor_location' => true,
        'services_html' => true,
        'picture' => true,
        'is_active' => true,
        'created' => true,
        'modified' => true,
        'services' => true,
        'staff' => true,
    ];
}
