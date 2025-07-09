<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateDoctorSchedules extends BaseMigration
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
        $table = $this->table('doctor_schedules');
        
        $table->addColumn('staff_id', 'integer', [
            'default' => null,
            'null' => false,
        ])
        ->addColumn('day_of_week', 'integer', [
            'default' => null,
            'null' => false,
            'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY,
            'comment' => '1-7, Monday-Sunday'
        ])
        ->addColumn('start_time', 'time', [
            'default' => null,
            'null' => false,
        ])
        ->addColumn('end_time', 'time', [
            'default' => null,
            'null' => false,
        ])
        ->addColumn('service_id', 'integer', [
            'default' => null,
            'null' => false,
        ])
        ->addColumn('max_appointments', 'integer', [
            'default' => 1,
            'null' => false,
        ])
        ->addColumn('slot_duration', 'integer', [
            'default' => null,
            'null' => true,
            'comment' => 'Override service duration if needed'
        ])
        ->addColumn('buffer_minutes', 'integer', [
            'default' => 0,
            'null' => false,
            'comment' => 'Minutes between appointments'
        ])
        ->addColumn('is_active', 'boolean', [
            'default' => true,
            'null' => false,
        ])
        ->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ])
        ->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ])
        ->addIndex(['staff_id'])
        ->addIndex(['service_id'])
        ->addIndex(['is_active', 'day_of_week'])
        ->addIndex(['staff_id', 'is_active'])
        ->addIndex(['staff_id', 'day_of_week', 'start_time', 'service_id'], [
            'unique' => true,
            'name' => 'unique_schedule'
        ])
        ->addForeignKey('staff_id', 'staff', 'id', [
            'delete' => 'CASCADE',
            'update' => 'CASCADE'
        ])
        ->addForeignKey('service_id', 'services', 'id', [
            'delete' => 'CASCADE',
            'update' => 'CASCADE'
        ])
        ->create();
    }
}
