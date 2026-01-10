<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\ORM\TableRegistry;
use Cake\View\Cell;

/**
 * Gallery cell
 */
class GalleryCell extends Cell
{
    /**
     * Default display method.
     *
     * @return void
     */
    public function display(): void
    {
        $sectionTitle = 'Galerie foto';
        $sectionDescription = '';

        // Fetch active gallery items from database
        $galleryItemsTable = TableRegistry::getTableLocator()->get('GalleryItems');
        $galleryItemsData = $galleryItemsTable->find('active')->toArray();

        // Transform to structured array for template
        $galleryItems = [];
        foreach ($galleryItemsData as $item) {
            $galleryItems[] = [
                'url' => $item->image_url,
                'title' => $item->title,
                'alt' => $item->alt_text ?: $item->title ?: '',
            ];
        }

        $this->set(compact('sectionTitle', 'sectionDescription', 'galleryItems'));
    }
}
