<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateSpecializations extends BaseMigration
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
        $table = $this->table('specializations');
        $table->addColumn('name', 'string', [
            'limit' => 100,
            'null' => false,
        ])
        ->addColumn('description', 'text', [
            'null' => true,
        ])
        ->addColumn('is_active', 'boolean', [
            'default' => true,
            'null' => false,
        ])
        ->addColumn('created', 'datetime', [
            'null' => false,
        ])
        ->addColumn('modified', 'datetime', [
            'null' => false,
        ])
        ->addIndex(['name'], ['unique' => true])
        ->addIndex(['is_active'])
        ->create();
    }
}
