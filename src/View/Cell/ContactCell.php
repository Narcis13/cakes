<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\View\Cell;

/**
 * Contact cell
 */
class ContactCell extends Cell
{
    /**
     * Default display method.
     *
     * @return void
     */
    public function display(): void
    {
        $sectionTitle = 'Contact';
        $sectionDescription = 'Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.';
        
        $mapEmbedUrl = 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12097.433213460943!2d-74.0062269!3d40.7101282!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb89d1fe6bc499443!2sDowntown+Conference+Center!5e0!3m2!1smk!2sbg!4v1539943755621';
        
        $contactInfo = [
            'address' => 'A108 Adam Street, New York, NY 535022',
            'email' => 'info@example.com',
            'phone' => '+1 5589 55488 55s'
        ];
        
        $this->set(compact('sectionTitle', 'sectionDescription', 'mapEmbedUrl', 'contactInfo'));
    }
}
