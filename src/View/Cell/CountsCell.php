<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\View\Cell;

/**
 * Counts cell
 */
class CountsCell extends Cell
{
    /**
     * Default display method.
     *
     * @return void
     */
    public function display(): void
    {
        $counts = [
            [
                'icon' => 'fas fa-user-md',
                'number' => 18521,
                'label' => 'Pacienti anual',
            ],
            [
                'icon' => 'far fa-hospital',
                'number' => 8210,
                'label' => 'Ex. Paraclinice',
            ],
            [
                'icon' => 'fas fa-flask',
                'number' => 9601,
                'label' => 'Consultatii',
            ],
            [
                'icon' => 'fas fa-award',
                'number' => 7158,
                'label' => 'Vizite medicale',
            ],
        ];

        $this->set(compact('counts'));
    }
}
