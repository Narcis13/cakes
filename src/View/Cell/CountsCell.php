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
                'number' => 85,
                'label' => 'Doctors'
            ],
            [
                'icon' => 'far fa-hospital',
                'number' => 18,
                'label' => 'Departments'
            ],
            [
                'icon' => 'fas fa-flask',
                'number' => 12,
                'label' => 'Research Labs'
            ],
            [
                'icon' => 'fas fa-award',
                'number' => 150,
                'label' => 'Awards'
            ]
        ];
        
        $this->set(compact('counts'));
    }
}
