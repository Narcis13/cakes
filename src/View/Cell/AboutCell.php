<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\View\Cell;

/**
 * About cell
 */
class AboutCell extends Cell
{
    /**
     * Default display method.
     *
     * @return void
     */
    public function display(): void
    {
        $videoUrl = 'https://www.youtube.com/watch?v=jDDaplaOz7Q';
        $title = 'Enim quis est voluptatibus aliquid consequatur fugiat';
        $description = 'Esse voluptas cumque vel exercitationem. Reiciendis est hic accusamus. Non ipsam et sed minima temporibus laudantium. Soluta voluptate sed facere corporis dolores excepturi. Libero laboriosam sint et id nulla tenetur. Suscipit aut voluptate.';
        
        $features = [
            [
                'icon' => 'bx bx-fingerprint',
                'title' => 'Lorem Ipsum',
                'description' => 'Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident'
            ],
            [
                'icon' => 'bx bx-gift',
                'title' => 'Nemo Enim',
                'description' => 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque'
            ],
            [
                'icon' => 'bx bx-atom',
                'title' => 'Dine Pad',
                'description' => 'Explicabo est voluptatum asperiores consequatur magnam. Et veritatis odit. Sunt aut deserunt minus aut eligendi omnis'
            ]
        ];
        
        $this->set(compact('videoUrl', 'title', 'description', 'features'));
    }
}
