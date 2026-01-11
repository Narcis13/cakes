<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\ORM\TableRegistry;
use Cake\View\Cell;

/**
 * Header cell
 */
class HeaderCell extends Cell
{
    /**
     * Default display method.
     *
     * @return void
     */
    public function display(): void
    {
        $navbarItems = $this->fetchTable('NavbarItems')->find('threaded')
            ->where(['is_active' => true])
            ->order(['sort_order' => 'ASC']);

        // Fetch appointment URL from settings
        $settingsTable = TableRegistry::getTableLocator()->get('Settings');
        $urlProgramari = $settingsTable->find()
            ->where(['key_name' => 'url_programari'])
            ->first()?->value ?? '/appointments';

        $this->set(compact('navbarItems', 'urlProgramari'));
    }
}
