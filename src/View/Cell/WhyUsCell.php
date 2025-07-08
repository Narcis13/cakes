<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\View\Cell;

/**
 * WhyUs cell
 */
class WhyUsCell extends Cell
{
    /**
     * Default display method.
     *
     * @return void
     */
    public function display(): void
    {
        $title = 'Why Choose Medilab?';
        $description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Duis aute irure dolor in reprehenderit Asperiores dolores sed et. Tenetur quia eos. Autem tempore quibusdam vel necessitatibus optio ad corporis.';

        $features = [
            [
                'icon' => 'bx bx-receipt',
                'title' => 'Corporis voluptates sit',
                'description' => 'Consequuntur sunt aut quasi enim aliquam quae harum pariatur laboris nisi ut aliquip',
            ],
            [
                'icon' => 'bx bx-cube-alt',
                'title' => 'Ullamco laboris ladore pan',
                'description' => 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt',
            ],
            [
                'icon' => 'bx bx-images',
                'title' => 'Labore consequatur',
                'description' => 'Aut suscipit aut cum nemo deleniti aut omnis. Doloribus ut maiores omnis facere',
            ],
        ];

        $this->set(compact('title', 'description', 'features'));
    }
}
