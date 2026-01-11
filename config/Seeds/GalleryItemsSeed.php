<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * GalleryItems seed.
 *
 * Seeds the 8 existing gallery images from the MediLab template.
 */
class GalleryItemsSeed extends BaseSeed
{
    /**
     * Run Method.
     *
     * @return void
     */
    public function run(): void
    {
        $data = [];
        $now = date('Y-m-d H:i:s');

        for ($i = 1; $i <= 8; $i++) {
            $data[] = [
                'image_url' => "/img/gallery/gallery-{$i}.jpg",
                'title' => "Spitalul Municipal - Imagine {$i}",
                'alt_text' => "Galerie foto spital - imagine {$i}",
                'sort_order' => $i,
                'is_active' => true,
                'created' => $now,
                'modified' => $now,
            ];
        }

        $table = $this->table('gallery_items');
        $table->insert($data)->save();
    }
}
