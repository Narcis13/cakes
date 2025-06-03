<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateDepartments extends BaseMigration
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
        $table = $this->table('departments');
        $table->addColumn('name', 'string', ['limit' => 100])
        ->addColumn('description', 'text', ['null' => true])
        ->addColumn('head_doctor_id', 'integer', ['null' => true])
        ->addColumn('phone', 'string', ['limit' => 20, 'null' => true])
        ->addColumn('email', 'string', ['limit' => 100, 'null' => true])
        ->addColumn('floor_location', 'string', ['limit' => 50, 'null' => true])
        ->addColumn('is_active', 'boolean', ['default' => true])
        ->addColumn('created', 'datetime')
        ->addColumn('modified', 'datetime')
        ->addIndex(['is_active'])
        ->create();
    }
}
