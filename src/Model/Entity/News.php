<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * News Entity
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string|null $excerpt
 * @property int|null $author_id
 * @property int|null $category_id
 * @property string|null $featured_image
 * @property bool $is_published
 * @property \Cake\I18n\DateTime|null $publish_date
 * @property int $views_count
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\Staff $author
 * @property \App\Model\Entity\NewsCategory $category
 */
class News extends Entity
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
        'title' => true,
        'slug' => true,
        'content' => true,
        'excerpt' => true,
        'author_id' => true,
        'category_id' => true,
        'featured_image' => true,
        'is_published' => true,
        'publish_date' => true,
        'views_count' => true,
        'created' => true,
        'modified' => true,
        'author' => true,
        'category' => true,
    ];
}
