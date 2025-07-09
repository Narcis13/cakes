<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateHospitalHolidays extends BaseMigration
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
        $table = $this->table('hospital_holidays');
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false,
        ])
        ->addColumn('date', 'date', [
            'default' => null,
            'null' => false,
        ])
        ->addColumn('is_recurring', 'boolean', [
            'default' => false,
            'null' => false,
        ])
        ->addColumn('description', 'text', [
            'default' => null,
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
        ->addIndex(['date'], ['unique' => true])
        ->create();
    }
}