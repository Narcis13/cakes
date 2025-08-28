<?php
declare(strict_types=1);

namespace App\View\Cell;

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

        $galleryItems = [
            '/img/gallery/gallery-1.jpg',
            '/img/gallery/gallery-2.jpg',
            '/img/gallery/gallery-3.jpg',
            '/img/gallery/gallery-4.jpg',
            '/img/gallery/gallery-5.jpg',
            '/img/gallery/gallery-6.jpg',
            '/img/gallery/gallery-7.jpg',
            '/img/gallery/gallery-8.jpg',
        ];

        $this->set(compact('sectionTitle', 'sectionDescription', 'galleryItems'));
    }
}
