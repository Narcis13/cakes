<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\Datasource\FactoryLocator;
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

        $mapEmbedUrl = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2823.123456789!2d24.8666667!3d44.8500000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40b2c7c4c5c5c5c5%3A0x5c5c5c5c5c5c5c5c!2sStrada%20Negru%20Voda%2047%2C%20Pite%C8%99ti%20110069%2C%20Romania!5e0!3m2!1sen!2sro!4v1234567890123';

        // Get contact info from database settings
        $settingsTable = FactoryLocator::get('Table')->get('Settings');

        $contactEmail = $settingsTable->find()
            ->where(['key_name' => 'contact_email'])
            ->first();

        $contactPhone = $settingsTable->find()
            ->where(['key_name' => 'contact_phone'])
            ->first();

        $contactInfo = [
            'address' => 'Arges, Pitesti, Str. Negru Voda nr. 47',
            'email' => $contactEmail ? $contactEmail->value : 'info@example.com',
            'phone' => $contactPhone ? $contactPhone->value : '+40 123 456 789',
        ];

        $this->set(compact('sectionTitle', 'sectionDescription', 'mapEmbedUrl', 'contactInfo'));
    }
}
