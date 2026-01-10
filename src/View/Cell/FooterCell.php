<?php
declare(strict_types=1);

namespace App\View\Cell;

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
        // Example: Fetch settings from database in the future
        // $settingsTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Settings');
        // $contactInfo = $settingsTable->find()->where(['key_name LIKE' => 'contact_%'])->toArray();
        // $socialLinks = $settingsTable->find()->where(['key_name LIKE' => 'social_%'])->toArray();

        // For now, using static data as in the original template
        $contactInfo = [
            'name' => 'SMUPitesti',
            'address' => 'Str. Negru Voda nr. 47',
            'city' => 'Pitesti, Arges',
            'country' => 'Romania',
            'phone' => '+40 248218090',
            'email' => 'smupitesti@mapn.ro',
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
