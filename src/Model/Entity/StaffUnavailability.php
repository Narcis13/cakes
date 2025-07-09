<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StaffUnavailability Entity
 *
 * @property int $id
 * @property int $staff_id
 * @property \Cake\I18n\Date $date_from
 * @property \Cake\I18n\Date $date_to
 * @property string|null $reason
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\Staff $staff
 */
class StaffUnavailability extends Entity
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
        'date_from' => true,
        'date_to' => true,
        'reason' => true,
        'created' => true,
        'modified' => true,
        'staff' => true,
    ];
}
