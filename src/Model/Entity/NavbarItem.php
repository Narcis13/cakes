<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * NavbarItem Entity
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $title
 * @property string|null $url
 * @property string|null $target
 * @property string|null $icon
 * @property int $sort_order
 * @property bool $is_active
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\NavbarItem $parent_navbar_item
 * @property \App\Model\Entity\NavbarItem[] $child_navbar_items
 */
class NavbarItem extends Entity
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
        'parent_id' => true,
        'title' => true,
        'url' => true,
        'target' => true,
        'icon' => true,
        'sort_order' => true,
        'is_active' => true,
        'created' => true,
        'modified' => true,
        'parent_navbar_item' => true,
        'child_navbar_items' => true,
    ];
}
