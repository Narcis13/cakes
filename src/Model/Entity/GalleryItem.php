<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GalleryItem Entity
 *
 * @property int $id
 * @property string $image_url
 * @property string|null $title
 * @property string|null $alt_text
 * @property int $sort_order
 * @property bool $is_active
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 */
class GalleryItem extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'image_url' => true,
        'title' => true,
        'alt_text' => true,
        'sort_order' => true,
        'is_active' => true,
        'created' => true,
        'modified' => true,
    ];
}
