<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\View\Cell;

/**
 * Doctors cell
 */
class DoctorsCell extends Cell
{
    /**
     * Default display method.
     *
     * @return void
     */
    public function display(): void
    {
        $sectionTitle = 'Doctors';
        $sectionDescription = 'Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.';
        
        $doctors = [
            [
                'name' => 'Walter White',
                'position' => 'Chief Medical Officer',
                'description' => 'Explicabo voluptatem mollitia et repellat qui dolorum quasi',
                'image' => '/img/doctors/doctors-1.jpg',
                'social' => [
                    'twitter' => '',
                    'facebook' => '',
                    'instagram' => '',
                    'linkedin' => ''
                ]
            ],
            [
                'name' => 'Sarah Jhonson',
                'position' => 'Anesthesiologist',
                'description' => 'Aut maiores voluptates amet et quis praesentium qui senda para',
                'image' => '/img/doctors/doctors-2.jpg',
                'social' => [
                    'twitter' => '',
                    'facebook' => '',
                    'instagram' => '',
                    'linkedin' => ''
                ]
            ],
            [
                'name' => 'William Anderson',
                'position' => 'Cardiology',
                'description' => 'Quisquam facilis cum velit laborum corrupti fuga rerum quia',
                'image' => '/img/doctors/doctors-3.jpg',
                'social' => [
                    'twitter' => '',
                    'facebook' => '',
                    'instagram' => '',
                    'linkedin' => ''
                ]
            ],
            [
                'name' => 'Amanda Jepson',
                'position' => 'Neurosurgeon',
                'description' => 'Dolorum tempora officiis odit laborum officiis et et accusamus',
                'image' => '/img/doctors/doctors-4.jpg',
                'social' => [
                    'twitter' => '',
                    'facebook' => '',
                    'instagram' => '',
                    'linkedin' => ''
                ]
            ]
        ];
        
        $this->set(compact('sectionTitle', 'sectionDescription', 'doctors'));
    }
}
