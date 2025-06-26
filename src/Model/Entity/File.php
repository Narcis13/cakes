<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * File Entity
 *
 * @property int $id
 * @property string $filename
 * @property string $original_name
 * @property string $file_path
 * @property string $file_url
 * @property string $mime_type
 * @property int $file_size
 * @property string $file_type
 * @property string|null $description
 * @property string|null $category
 * @property bool $is_public
 * @property int $download_count
 * @property int|null $uploaded_by
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 */
class File extends Entity
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
        'filename' => true,
        'original_name' => true,
        'file_path' => true,
        'file_url' => true,
        'mime_type' => true,
        'file_size' => true,
        'file_type' => true,
        'description' => true,
        'category' => true,
        'is_public' => true,
        'download_count' => true,
        'uploaded_by' => true,
        'created' => true,
        'modified' => true,
    ];
}
