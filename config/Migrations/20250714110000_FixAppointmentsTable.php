<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class FixAppointmentsTable extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('appointments');
        
        // Add patient_cnp column if it doesn't exist
        if (!$table->hasColumn('patient_cnp')) {
            $table->addColumn('patient_cnp', 'string', [
                'default' => null,
                'limit' => 13,
                'null' => true,
                'after' => 'patient_email'
            ]);
        }
        
        // Change appointment_date from datetime to date
        $table->changeColumn('appointment_date', 'date', [
            'default' => null,
            'null' => false,
        ]);
        
        $table->update();
    }
}