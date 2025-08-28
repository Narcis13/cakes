<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\View\Cell;

/**
 * Departments cell
 */
class DepartmentsCell extends Cell
{
    use LocatorAwareTrait;

    /**
     * Default display method.
     *
     * @return void
     */
    public function display(): void
    {
        $departmentsTable = $this->getTableLocator()->get('Departments');

        $sectionTitle = 'Sectii / compartimente';
        $sectionDescription = 'Spitalul nostru ofera servicii medicale complete prin intermediul sectiilor specializate, dotate cu echipamente de ultima generatie si personal medical competent.';

        $departmentData = $departmentsTable->find()
            ->where(['is_active' => true])
            ->orderBy(['name' => 'ASC'])
            ->toArray();

        $departments = [];
        $isFirst = true;

        foreach ($departmentData as $index => $dept) {
            $departments[] = [
                'id' => 'tab-' . ($index + 1),
                'name' => $dept->name,
                'active' => $isFirst,
                'title' => $dept->name,
                'subtitle' => $dept->description ? substr(strip_tags($dept->description), 0, 100) . '...' : 'Professional healthcare services',
                'description' => $dept->services_html ?: $dept->description ?: 'Comprehensive medical care provided by our experienced team of healthcare professionals.',
                'image' => $dept->picture ?: 'https://via.placeholder.com/800x600/4CAF50/ffffff.png',
            ];
            $isFirst = false;
        }

        if (empty($departments)) {
            $departments = [
                [
                    'id' => 'tab-1',
                    'name' => 'General Medicine',
                    'active' => true,
                    'title' => 'General Medicine',
                    'subtitle' => 'Comprehensive primary healthcare services',
                    'description' => 'Our General Medicine department provides comprehensive primary healthcare services with experienced physicians.',
                    'image' => 'https://via.placeholder.com/800x600/4CAF50/ffffff.png',
                ],
            ];
        }

        $this->set(compact('sectionTitle', 'sectionDescription', 'departments'));
    }
}
