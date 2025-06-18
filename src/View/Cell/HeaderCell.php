<?php
declare(strict_types=1);

namespace App\View\Cell;

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

        $this->set(compact('navbarItems'));
    }
}
