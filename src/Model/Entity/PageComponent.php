<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PageComponent Entity
 *
 * @property int $id
 * @property int $page_id
 * @property string $type
 * @property string|null $content
 * @property string|null $title
 * @property string|null $url
 * @property string|null $button_caption
 * @property string|null $alt_text
 * @property string|null $css_class
 * @property string|null $image_type
 * @property int $sort_order
 * @property bool $is_active
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\Page $page
 */
class PageComponent extends Entity
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
        'page_id' => true,
        'type' => true,
        'content' => true,
        'title' => true,
        'url' => true,
        'button_caption' => true,
        'alt_text' => true,
        'css_class' => true,
        'image_type' => true,
        'sort_order' => true,
        'is_active' => true,
        'created' => true,
        'modified' => true,
        'page' => true,
    ];
}
