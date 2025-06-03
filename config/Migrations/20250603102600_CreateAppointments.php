<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateAppointments extends BaseMigration
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
        $table->addColumn('patient_name', 'string', ['limit' => 100])
              ->addColumn('patient_phone', 'string', ['limit' => 20])
              ->addColumn('patient_email', 'string', ['limit' => 100, 'null' => true])
              ->addColumn('service_id', 'integer', ['null' => true])
              ->addColumn('doctor_id', 'integer', ['null' => true])
              ->addColumn('appointment_date', 'datetime')
              ->addColumn('status', 'string', ['limit' => 20, 'default' => 'pending'])
              ->addColumn('notes', 'text', ['null' => true])
              ->addColumn('created', 'datetime')
              ->addColumn('modified', 'datetime')
              ->addForeignKey('service_id', 'services', 'id', ['delete' => 'SET_NULL'])
              ->addForeignKey('doctor_id', 'staff', 'id', ['delete' => 'SET_NULL'])
              ->addIndex(['appointment_date'])
              ->addIndex(['status'])
              ->create();
    }
}
