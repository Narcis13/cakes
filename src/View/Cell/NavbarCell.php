<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\View\Cell;

class NavbarCell extends Cell
{
    public function display($currentPage = null): void
    {
        $menuItems = [
            'Home' => '/',
            'Services' => '/services',
            'Doctors' => '/doctors',
            'About' => '/about',
            'Contact' => '/contact',
        ];

        $this->set('menuItems', $menuItems);
        $this->set('currentPage', $currentPage);
    }
}
