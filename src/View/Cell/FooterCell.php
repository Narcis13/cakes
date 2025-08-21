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
            'name' => 'Medilab',
            'address' => 'A109 Adam Street',
            'city' => 'New York, NY 535022',
            'country' => 'United States',
            'phone' => '+1 5589 55488 55',
            'email' => 'info@example.com',
        ];

        $usefulLinks = [
            ['title' => 'Acasa', 'url' => '/'],
            ['title' => 'Ministerul Sanatatii', 'url' => 'https://www.ms.ro'],
            ['title' => 'Programari telefonice', 'url' => '/services'],
            ['title' => 'TerTermeni si conditii', 'url' => '/terms'],
            ['title' => 'Politica confidentialitate', 'url' => '/privacy'],
        ];

        $serviceLinks = [
            ['title' => 'Asociatia SANIVITAL', 'url' => '#'],
            ['title' => 'Web Development', 'url' => '#'],
            ['title' => 'Product Management', 'url' => '#'],
            ['title' => 'Marketing', 'url' => '#'],
            ['title' => 'Graphic Design', 'url' => '#'],
        ];

        $socialLinks = [
            'twitter' => '#',
            'facebook' => '#',
            'instagram' => '#',
            'skype' => '#',
            'linkedin' => '#',
        ];

        $newsletterText = 'Tamen quem nulla quae legam multos aute sint culpa legam noster magna';
        $copyright = '&copy; Copyright <strong><span>Medilab</span></strong>. All Rights Reserved';
        $credits = 'Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>';

        $this->set(compact('contactInfo', 'usefulLinks', 'serviceLinks', 'socialLinks', 'newsletterText', 'copyright', 'credits'));
    }
}
