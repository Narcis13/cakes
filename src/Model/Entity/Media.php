<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Media Entity
 *
 * @property int $id
 * @property string $filename
 * @property string $original_name
 * @property string $mime_type
 * @property int $size
 * @property string|null $alt_text
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 */
class Media extends Entity
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
        'mime_type' => true,
        'size' => true,
        'alt_text' => true,
        'created' => true,
        'modified' => true,
    ];
}
