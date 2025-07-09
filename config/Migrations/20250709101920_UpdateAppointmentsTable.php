<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class UpdateAppointmentsTable extends BaseMigration
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
        
        // Add new columns
        $table->addColumn('appointment_time', 'time', [
            'default' => null,
            'null' => false,
            'after' => 'appointment_date'
        ])
        ->addColumn('end_time', 'time', [
            'default' => null,
            'null' => false,
            'after' => 'appointment_time'
        ])
        ->addColumn('confirmation_token', 'string', [
            'default' => null,
            'limit' => 64,
            'null' => true,
        ])
        ->addColumn('confirmed_at', 'datetime', [
            'default' => null,
            'null' => true,
        ])
        ->addColumn('cancelled_at', 'datetime', [
            'default' => null,
            'null' => true,
        ])
        ->addColumn('cancellation_reason', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ])
        ->addIndex(['confirmation_token'])
        ->addIndex(['appointment_date', 'appointment_time'])
        ->addIndex(['doctor_id', 'appointment_date', 'status'])
        ->update();
    }
}
