<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Staff Entity
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $title
 * @property string|null $specialization
 * @property int|null $department_id
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $bio
 * @property string|null $photo
 * @property int|null $years_experience
 * @property string $staff_type
 * @property bool $is_active
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\Department $department
 * @property string $name Virtual field for full name
 */
class Staff extends Entity
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
        'first_name' => true,
        'last_name' => true,
        'title' => true,
        'specialization' => true,
        'department_id' => true,
        'phone' => true,
        'email' => true,
        'bio' => true,
        'photo' => true,
        'years_experience' => true,
        'staff_type' => true,
        'is_active' => true,
        'created' => true,
        'modified' => true,
        'department' => true,
    ];

    /**
     * Virtual field for full name
     *
     * @return string
     */
    protected function _getName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
