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
        $sectionTitle = 'Servicii medicale';
        $sectionDescription = 'Unitatea noastră oferă o gamă largă de servicii medicale pentru a răspunde nevoilor pacienților.';

        $services = [
            [
                'icon' => 'fas fa-heartbeat',
                'title' => 'SPITAL',
                'description' => 'Servicii medicale spitalizare continuă și de zi pe secții medicale și chirurgicale',
            ],
            [
                'icon' => 'fas fa-pills',
                'title' => 'AMBULATORIU',
                'description' => 'Servicii medicale clinice, consultații în ambulatoriu de specialitate',
            ],
            [
                'icon' => 'fas fa-hospital-user',
                'title' => 'INVESTIGAȚII PARACLINICE',
                'description' => 'Investigații paraclinice complete, inclusiv analize de laborator și imagistică medicală',
            ],
            [
                'icon' => 'fas fa-dna',
                'title' => 'STOMATOLOGIE',
                'description' => 'Servicii stomatologice complete, inclusiv consultații, tratamente.',
            ],
            [
                'icon' => 'fas fa-wheelchair',
                'title' => 'MEDICINA DE FAMILIE',
                'description' => 'Servicii medicină primară prin cabinete de medicină de familie',
            ],
            [
                'icon' => 'fas fa-notes-medical',
                'title' => 'ALTE SERVICII',
                'description' => 'Servicii medicale la cerere, fișe medicale',
            ],
        ];

        $this->set(compact('sectionTitle', 'sectionDescription', 'services'));
    }
}
