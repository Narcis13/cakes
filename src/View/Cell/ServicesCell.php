<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\View\Cell;

/**
 * Services cell
 */
class ServicesCell extends Cell
{
    /**
     * Default display method.
     *
     * @return void
     */
    public function display(): void
    {
        $sectionTitle = 'Services';
        $sectionDescription = 'Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.';
        
        $services = [
            [
                'icon' => 'fas fa-heartbeat',
                'title' => 'Lorem Ipsum',
                'description' => 'Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi'
            ],
            [
                'icon' => 'fas fa-pills',
                'title' => 'Sed ut perspiciatis',
                'description' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore'
            ],
            [
                'icon' => 'fas fa-hospital-user',
                'title' => 'Magni Dolores',
                'description' => 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia'
            ],
            [
                'icon' => 'fas fa-dna',
                'title' => 'Nemo Enim',
                'description' => 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis'
            ],
            [
                'icon' => 'fas fa-wheelchair',
                'title' => 'Dele cardo',
                'description' => 'Quis consequatur saepe eligendi voluptatem consequatur dolor consequuntur'
            ],
            [
                'icon' => 'fas fa-notes-medical',
                'title' => 'Divera don',
                'description' => 'Modi nostrum vel laborum. Porro fugit error sit minus sapiente sit aspernatur'
            ]
        ];
        
        $this->set(compact('sectionTitle', 'sectionDescription', 'services'));
    }
}
