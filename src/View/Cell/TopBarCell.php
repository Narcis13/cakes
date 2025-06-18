<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\View\Cell;
// Uncomment and use TableRegistry if you plan to fetch data from DB
// use Cake\ORM\TableRegistry;

/**
 * TopBar cell
 */
class TopBarCell extends Cell
{
    /**
     * Default display method.
     *
     * Fetches data for the top bar and sets it for the template.
     *
     * @return void
     */
    public function display(): void
    {
        // Example: Fetch settings from database in the future
        $settingsTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Settings');
        $contactEmail = $settingsTable->find()->where(['key_name' => 'contact_email'])->firstOrFail()->value;
        $contactPhone = $settingsTable->find()->where(['key_name' => 'contact_phone'])->firstOrFail()->value;
        // $socialLinks = [
        //    'youtube' => $settingsTable->find()->where(['key_name' => 'social_youtube_url'])->firstOrFail()->value,
        //    'facebook' => $settingsTable->find()->where(['key_name' => 'social_facebook_url'])->firstOrFail()->value,
        //    // ... etc.
        // ];
        // $this->set(compact('contactEmail', 'contactPhone', 'socialLinks'));

        // For now, using static data as in the original template
      //  $contactEmail = 'contact13@example.com';
       // $contactPhone = '+1 5589 55488 55';
        $socialLinks = [
            'youtube' => '#', // Original had twitter class but bi-youtube icon
            'facebook' => '#',
            'instagram' => '#',
            'linkedin' => '#',
        ];
        $this->set(compact('contactEmail', 'contactPhone', 'socialLinks'));
    }
}