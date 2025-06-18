<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateNavbarItems extends BaseMigration
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
        $table = $this->table('navbar_items');
        $table->addColumn('parent_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->addColumn('title', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('url', 'string', [
            'default' => null,
            'limit' => 500,
            'null' => true,
        ]);
        $table->addColumn('target', 'string', [
            'default' => '_self',
            'limit' => 50,
            'null' => true,
        ]);
        $table->addColumn('icon', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => true,
        ]);
        $table->addColumn('sort_order', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('is_active', 'boolean', [
            'default' => true,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addIndex([
            'parent_id',
        ], [
            'name' => 'BY_PARENT_ID',
        ]);
        $table->addIndex([
            'sort_order',
        ], [
            'name' => 'BY_SORT_ORDER',
        ]);
        $table->addIndex([
            'is_active',
        ], [
            'name' => 'BY_IS_ACTIVE',
        ]);
        $table->create();
    }
}
