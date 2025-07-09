<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateScheduleExceptions extends BaseMigration
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
        $table = $this->table('schedule_exceptions');
        
        $table->addColumn('staff_id', 'integer', [
            'default' => null,
            'null' => false,
        ])
        ->addColumn('exception_date', 'date', [
            'default' => null,
            'null' => false,
        ])
        ->addColumn('is_working', 'boolean', [
            'default' => false,
            'null' => false,
        ])
        ->addColumn('start_time', 'time', [
            'default' => null,
            'null' => true,
        ])
        ->addColumn('end_time', 'time', [
            'default' => null,
            'null' => true,
        ])
        ->addColumn('reason', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
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
        ->addIndex(['exception_date'])
        ->addIndex(['staff_id', 'exception_date'])
        ->addIndex(['staff_id', 'exception_date'], [
            'unique' => true,
            'name' => 'unique_exception'
        ])
        ->addForeignKey('staff_id', 'staff', 'id', [
            'delete' => 'CASCADE',
            'update' => 'CASCADE'
        ])
        ->create();
    }
}
