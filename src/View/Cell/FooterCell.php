<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\ORM\TableRegistry;
use Cake\View\Cell;

/**
 * Footer cell
 */
class FooterCell extends Cell
{
    /**
     * Default display method.
     *
     * Fetches data for the footer and sets it for the template.
     *
     * @return void
     */
    public function display(): void
    {
        // Fetch contact settings from database
        $settingsTable = TableRegistry::getTableLocator()->get('Settings');
        $contactPhone = $settingsTable->find()->where(['key_name' => 'contact_phone'])->first()?->value ?? '';
        $contactEmail = $settingsTable->find()->where(['key_name' => 'contact_email'])->first()?->value ?? '';

        $contactInfo = [
            'name' => 'SMUPitesti',
            'address' => 'Str. Negru Voda nr. 47',
            'city' => 'Pitesti, Arges',
            'country' => 'Romania',
            'phone' => $contactPhone,
            'email' => $contactEmail,
        ];

        $usefulLinks = [
            ['title' => 'Acasa', 'url' => '/'],
            ['title' => 'Ministerul Sanatatii', 'url' => 'https://www.ms.ro'],
            ['title' => 'Programari telefonice', 'url' => '/programari-telefonice'],
            ['title' => 'Termeni si conditii', 'url' => '/terms'],
            ['title' => 'Politica confidentialitate', 'url' => '/politica-de-confidentialitate'],
        ];

        $serviceLinks = [
            ['title' => 'Asociatia SANIVITAL', 'url' => '#'],
            ['title' => 'Servicii contra cost', 'url' => '#'],
            ['title' => 'Relatii cu publicul', 'url' => '#'],
            ['title' => 'Comunitate', 'url' => '#'],
            ['title' => 'CSM Tirgoviste', 'url' => '#'],
        ];

        $socialLinks = [
            'twitter' => '#',
            'facebook' => 'https://www.facebook.com/spitalulmilitarpitesti',
            'instagram' => '#',
            'skype' => '#',
            'linkedin' => '#',
        ];

        $newsletterText = 'Tamen quem nulla quae legam multos aute sint culpa legam noster magna';
        $copyright = '&copy; Copyright <strong><span>Spitalul Militar de Urgenta Dr. Ion Jianu Pitesti</span></strong>. All Rights Reserved';
        $credits = 'Designed by <a href="#">SMUP</a>';

        $this->set(compact('contactInfo', 'usefulLinks', 'serviceLinks', 'socialLinks', 'newsletterText', 'copyright', 'credits'));
    }
}
