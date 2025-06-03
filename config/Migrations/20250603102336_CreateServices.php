<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateServices extends BaseMigration
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
        $table = $this->table('services');
        $table->addColumn('name', 'string', ['limit' => 100])
              ->addColumn('description', 'text', ['null' => true])
              ->addColumn('department_id', 'integer', ['null' => true])
              ->addColumn('duration_minutes', 'integer', ['null' => true])
              ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => true])
              ->addColumn('requirements', 'text', ['null' => true])
              ->addColumn('is_active', 'boolean', ['default' => true])
              ->addColumn('created', 'datetime')
              ->addColumn('modified', 'datetime')
              ->addForeignKey('department_id', 'departments', 'id', ['delete' => 'SET_NULL'])
              ->addIndex(['department_id'])
              ->addIndex(['is_active'])
              ->create();
    }
}
