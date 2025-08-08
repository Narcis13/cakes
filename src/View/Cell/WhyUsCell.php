<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\ORM\TableRegistry;
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
        $title = 'Program vizita';
        $settingsTable = TableRegistry::getTableLocator()->get('Settings');
        $description = $settingsTable->find()->where(['key_name' => 'program_vizita'])->firstOrFail()->value;
        $features = [
            [
                'icon' => 'bx bx-receipt',
                'title' => 'Ambulatoriu integrat',
                'description' => 'Pacientii pot beneficia de servicii medicale în ambulatoriu pe baza biletului de trimitere de la medicul de familie.',
            ],
            [
                'icon' => 'bx bx-receipt',
                'title' => 'Compartiment primiri urgente',
                'description' => 'Echipa de medici specialisti cu competenta in medicina de urgenta, asistente medicale si personal special antrenat in acest domeniu este disponibila in permanenta oricui necesita ingrijire de urgenta, furnizand asistenta si servicii medicale de calitate

',
            ],
            [
                'icon' => 'bx bx-images',
                'title' => 'Radiologie si imagistica',
                'description' => 'Servicii de radiologie si imagistica medicala, inclusiv ecografie, tomografie computerizata si rezonanta magnetica, pentru diagnosticarea precisa a afectiunilor pacientilor.',
            ],
        ];

        $this->set(compact('title', 'description', 'features'));
    }
}
