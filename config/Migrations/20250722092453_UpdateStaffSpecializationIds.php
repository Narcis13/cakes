<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class UpdateStaffSpecializationIds extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     * @return void
     */
    public function up(): void
    {
        // Get specializations
        $specializations = $this->fetchAll("SELECT id, name FROM specializations");
        $specMap = [];
        foreach ($specializations as $spec) {
            $specMap[$spec['name']] = $spec['id'];
        }

        // Update existing staff with correct specialization_id
        $existingStaff = $this->fetchAll("SELECT id, specialization FROM staff WHERE specialization IS NOT NULL");
        
        foreach ($existingStaff as $staff) {
            if (isset($specMap[$staff['specialization']])) {
                $this->execute("UPDATE staff SET specialization_id = {$specMap[$staff['specialization']]} WHERE id = {$staff['id']}");
            }
        }
    }

    public function down(): void
    {
        // No need to reverse as we're just updating data
    }
}
