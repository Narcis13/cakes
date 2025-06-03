<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateMenus extends BaseMigration
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
        $table = $this->table('menus');
        $table->addColumn('title', 'string', ['limit' => 100])
              ->addColumn('url', 'string', ['limit' => 255])
              ->addColumn('parent_id', 'integer', ['null' => true])
              ->addColumn('sort_order', 'integer', ['default' => 0])
              ->addColumn('is_active', 'boolean', ['default' => true])
              ->addColumn('created', 'datetime')
              ->addColumn('modified', 'datetime')
              ->addForeignKey('parent_id', 'menus', 'id', ['delete' => 'CASCADE'])
              ->addIndex(['parent_id'])
              ->addIndex(['sort_order'])
              ->create();
    }
}
