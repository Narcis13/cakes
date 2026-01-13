<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddPatientIdToAppointments extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('appointments');
        $table
            ->addColumn('patient_id', 'integer', ['null' => true, 'default' => null, 'after' => 'id'])
            ->addForeignKey('patient_id', 'patients', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'CASCADE',
            ])
            ->addIndex(['patient_id'], ['name' => 'idx_appointments_patient_id'])
            ->update();
    }
}
