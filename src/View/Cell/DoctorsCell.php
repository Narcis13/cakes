<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\ORM\TableRegistry;
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
        $sectionTitle = 'Medicii noștri';
        $sectionDescription = 'Profesioniștii noștri experimentați dedicați furnizării de servicii de sănătate excepționale.';

        $staffTable = TableRegistry::getTableLocator()->get('Staff');

        $staffMembers = $staffTable->find()
            ->contain(['Departments'])
            ->where([
                'Staff.staff_type' => 'doctor',
                'Staff.is_active' => true,
            ])
            ->orderAsc('Staff.last_name')
            ->toArray();

        $doctors = [];
        foreach ($staffMembers as $staff) {
            // Generate image path with fallback
            $imagePath = '/img/doctors/default-doctor.jpg'; // Default fallback
            if ($staff->photo) {
                $imagePath = '/img/staff/' . $staff->photo;
            }

            $doctors[] = [
                'name' => $staff->first_name . ' ' . $staff->last_name,
                'position' => $staff->title ?: $staff->specialization ?: 'Doctor',
                'description' => $staff->bio ?: 'Experienced medical professional dedicated to patient care.',
                'image' => $imagePath,
                'initials' => substr($staff->first_name, 0, 1) . substr($staff->last_name, 0, 1),
                'hasPhoto' => !empty($staff->photo),
                'social' => [
                    'twitter' => '',
                    'facebook' => '',
                    'instagram' => '',
                    'linkedin' => '',
                ],
                'department' => $staff->department ? $staff->department->name : '',
                'experience' => $staff->years_experience,
                'phone' => $staff->phone,
                'email' => $staff->email,
            ];
        }

        $doctorSlides = array_chunk($doctors, 4);
        $totalSlides = count($doctorSlides);

        $this->set(compact('sectionTitle', 'sectionDescription', 'doctors', 'doctorSlides', 'totalSlides'));
    }
}
