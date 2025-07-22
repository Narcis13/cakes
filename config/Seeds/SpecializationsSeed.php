<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * Specializations seed.
 */
class SpecializationsSeed extends BaseSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/migrations/4/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Cardiologie',
                'description' => 'Specializare în bolile inimii și ale sistemului cardiovascular',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Neurologie',
                'description' => 'Specializare în bolile sistemului nervos',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Pediatrie',
                'description' => 'Îngrijirea medicală a sugarilor, copiilor și adolescenților',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Chirurgie Generală',
                'description' => 'Proceduri chirurgicale pentru diverse afecțiuni',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Medicina Internă',
                'description' => 'Prevenirea, diagnosticarea și tratamentul bolilor la adulți',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Ortopedie',
                'description' => 'Afecțiuni ale sistemului musculo-scheletal',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Dermatologie',
                'description' => 'Bolile pielii, părului și unghiilor',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Ginecologie',
                'description' => 'Sănătatea sistemului reproducător feminin',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Oftalmologie',
                'description' => 'Afecțiuni ale ochilor și vederii',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'ORL (Otorinolaringologie)',
                'description' => 'Bolile urechii, nasului și gâtului',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Radiologie',
                'description' => 'Imagistică medicală și interpretare',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Anestezie și Terapie Intensivă',
                'description' => 'Anestezie și îngrijire critică',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Psihiatrie',
                'description' => 'Sănătate mintală și tulburări psihice',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Medicină de Familie',
                'description' => 'Îngrijire medicală primară și preventivă',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Endocrinologie',
                'description' => 'Sistemul endocrin și tulburările hormonale',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Gastroenterologie',
                'description' => 'Sistemul digestiv și afecțiunile sale',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Pneumologie',
                'description' => 'Bolile plămânilor și ale căilor respiratorii',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Reumatologie',
                'description' => 'Bolile articulațiilor, mușchilor și țesutului conjunctiv',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Urologie',
                'description' => 'Sistemul urinar și sistemul reproducător masculin',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Oncologie',
                'description' => 'Diagnosticarea și tratamentul cancerului',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];

        $table = $this->table('specializations');
        $table->insert($data)->save();
    }
}
