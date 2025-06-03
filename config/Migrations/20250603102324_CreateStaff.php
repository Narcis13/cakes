<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateStaff extends BaseMigration
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
        $table = $this->table('staff');
        $table->addColumn('first_name', 'string', ['limit' => 50])
        ->addColumn('last_name', 'string', ['limit' => 50])
        ->addColumn('title', 'string', ['limit' => 100, 'null' => true])
        ->addColumn('specialization', 'string', ['limit' => 100, 'null' => true])
        ->addColumn('department_id', 'integer', ['null' => true])
        ->addColumn('phone', 'string', ['limit' => 20, 'null' => true])
        ->addColumn('email', 'string', ['limit' => 100, 'null' => true])
        ->addColumn('bio', 'text', ['null' => true])
        ->addColumn('photo', 'string', ['limit' => 255, 'null' => true])
        ->addColumn('years_experience', 'integer', ['null' => true])
        ->addColumn('staff_type', 'string', ['limit' => 20, 'default' => 'doctor'])
        ->addColumn('is_active', 'boolean', ['default' => true])
        ->addColumn('created', 'datetime')
        ->addColumn('modified', 'datetime')
        ->addForeignKey('department_id', 'departments', 'id', ['delete' => 'SET_NULL'])
        ->addIndex(['department_id'])
        ->addIndex(['staff_type'])
        ->addIndex(['is_active'])
        ->create();
    }
}
