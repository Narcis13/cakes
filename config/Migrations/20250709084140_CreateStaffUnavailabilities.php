<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateStaffUnavailabilities extends BaseMigration
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
        $table = $this->table('staff_unavailabilities');
        $table->addColumn('staff_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ])
        ->addColumn('date_from', 'date', [
            'default' => null,
            'null' => false,
        ])
        ->addColumn('date_to', 'date', [
            'default' => null,
            'null' => false,
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
        ->addIndex(['date_from'])
        ->addIndex(['date_to'])
        ->addForeignKey('staff_id', 'staff', 'id', [
            'delete' => 'CASCADE',
            'update' => 'NO_ACTION'
        ])
        ->create();
    }
}
