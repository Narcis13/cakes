<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\View\Cell;

/**
 * Appointment cell
 */
class AppointmentCell extends Cell
{
    /**
     * Default display method.
     *
     * @return void
     */
    public function display(): void
    {
        $sectionTitle = 'Make an Appointment';
        $sectionDescription = 'Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.';
        
        $departments = [
            'Department 1',
            'Department 2',
            'Department 3'
        ];
        
        $doctors = [
            'Doctor 1',
            'Doctor 2',
            'Doctor 3'
        ];
        
        $this->set(compact('sectionTitle', 'sectionDescription', 'departments', 'doctors'));
    }
}
