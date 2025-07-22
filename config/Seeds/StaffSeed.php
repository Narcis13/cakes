<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * Staff seed.
 */
class StaffSeed extends BaseSeed
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
        // First, get specialization IDs
        $conn = $this->getAdapter()->getConnection();
        $specializations = $conn->execute("SELECT id, name FROM specializations")->fetchAll('assoc');
        $specMap = [];
        foreach ($specializations as $spec) {
            $specMap[$spec['name']] = $spec['id'];
        }

        $data = [
            // Cardiologie
            [
                'first_name' => 'Alexandru',
                'last_name' => 'Popescu',
                'title' => 'Dr.',
                'specialization' => 'Cardiologie',
                'specialization_id' => $specMap['Cardiologie'] ?? null,
                'department_id' => null,
                'phone' => '0721 123 456',
                'email' => 'alexandru.popescu@spital.ro',
                'bio' => 'Specialist în cardiologie cu peste 15 ani de experiență în tratamentul bolilor cardiovasculare.',
                'years_experience' => 15,
                'staff_type' => 'doctor',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            // Neurologie
            [
                'first_name' => 'Maria',
                'last_name' => 'Ionescu',
                'title' => 'Dr.',
                'specialization' => 'Neurologie',
                'specialization_id' => $specMap['Neurologie'] ?? null,
                'department_id' => null,
                'phone' => '0722 234 567',
                'email' => 'maria.ionescu@spital.ro',
                'bio' => 'Medic primar neurolog, specializat în tratamentul bolilor neurodegenerative.',
                'years_experience' => 12,
                'staff_type' => 'doctor',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            // Pediatrie
            [
                'first_name' => 'Andrei',
                'last_name' => 'Dumitrescu',
                'title' => 'Dr.',
                'specialization' => 'Pediatrie',
                'specialization_id' => $specMap['Pediatrie'] ?? null,
                'department_id' => null,
                'phone' => '0723 345 678',
                'email' => 'andrei.dumitrescu@spital.ro',
                'bio' => 'Medic specialist pediatru cu experiență în neonatologie și pediatrie generală.',
                'years_experience' => 10,
                'staff_type' => 'doctor',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            // Chirurgie Generală
            [
                'first_name' => 'Mihai',
                'last_name' => 'Radu',
                'title' => 'Dr.',
                'specialization' => 'Chirurgie Generală',
                'specialization_id' => $specMap['Chirurgie Generală'] ?? null,
                'department_id' => null,
                'phone' => '0724 456 789',
                'email' => 'mihai.radu@spital.ro',
                'bio' => 'Chirurg cu experiență în chirurgie laparoscopică și chirurgie oncologică.',
                'years_experience' => 18,
                'staff_type' => 'doctor',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            // Oftalmologie - existing doctor
            [
                'first_name' => 'Elena',
                'last_name' => 'Constantinescu',
                'title' => 'Dr.',
                'specialization' => 'Oftalmologie',
                'specialization_id' => $specMap['Oftalmologie'] ?? null,
                'department_id' => null,
                'phone' => '0725 567 890',
                'email' => 'elena.constantinescu@spital.ro',
                'bio' => 'Medic primar oftalmolog, specializat în chirurgia cataractei și chirurgie refractivă.',
                'years_experience' => 20,
                'staff_type' => 'doctor',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            // Ginecologie
            [
                'first_name' => 'Ana',
                'last_name' => 'Popa',
                'title' => 'Dr.',
                'specialization' => 'Ginecologie',
                'specialization_id' => $specMap['Ginecologie'] ?? null,
                'department_id' => null,
                'phone' => '0726 678 901',
                'email' => 'ana.popa@spital.ro',
                'bio' => 'Specialist în obstetrică-ginecologie cu experiență în medicina materno-fetală.',
                'years_experience' => 14,
                'staff_type' => 'doctor',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            // Ortopedie
            [
                'first_name' => 'Bogdan',
                'last_name' => 'Stoica',
                'title' => 'Dr.',
                'specialization' => 'Ortopedie',
                'specialization_id' => $specMap['Ortopedie'] ?? null,
                'department_id' => null,
                'phone' => '0727 789 012',
                'email' => 'bogdan.stoica@spital.ro',
                'bio' => 'Medic ortoped specializat în chirurgia artroscopică și protezare articulară.',
                'years_experience' => 16,
                'staff_type' => 'doctor',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            // Dermatologie
            [
                'first_name' => 'Cristina',
                'last_name' => 'Georgescu',
                'title' => 'Dr.',
                'specialization' => 'Dermatologie',
                'specialization_id' => $specMap['Dermatologie'] ?? null,
                'department_id' => null,
                'phone' => '0728 890 123',
                'email' => 'cristina.georgescu@spital.ro',
                'bio' => 'Medic dermatolog cu experiență în dermatologie estetică și tratamentul cancerului de piele.',
                'years_experience' => 11,
                'staff_type' => 'doctor',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            // ORL
            [
                'first_name' => 'Dan',
                'last_name' => 'Marinescu',
                'title' => 'Dr.',
                'specialization' => 'ORL (Otorinolaringologie)',
                'specialization_id' => $specMap['ORL (Otorinolaringologie)'] ?? null,
                'department_id' => null,
                'phone' => '0729 901 234',
                'email' => 'dan.marinescu@spital.ro',
                'bio' => 'Specialist ORL cu experiență în chirurgia endoscopică sinusală și chirurgia otologică.',
                'years_experience' => 13,
                'staff_type' => 'doctor',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            // Medicină de Familie
            [
                'first_name' => 'Ioana',
                'last_name' => 'Nistor',
                'title' => 'Dr.',
                'specialization' => 'Medicină de Familie',
                'specialization_id' => $specMap['Medicină de Familie'] ?? null,
                'department_id' => null,
                'phone' => '0730 012 345',
                'email' => 'ioana.nistor@spital.ro',
                'bio' => 'Medic de familie cu abordare holistică în îngrijirea pacienților.',
                'years_experience' => 8,
                'staff_type' => 'doctor',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];

        $table = $this->table('staff');
        $table->insert($data)->save();
    }
}
