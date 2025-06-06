<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateNewsCategories extends BaseMigration
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
        $table = $this->table('news_categories');
        $table->addColumn('name', 'string', ['limit' => 100])
              ->addColumn('slug', 'string', ['limit' => 100])
              ->addColumn('description', 'text', ['null' => true])
              ->addColumn('created', 'datetime')
              ->addColumn('modified', 'datetime')
              ->addIndex(['slug'], ['unique' => true])
              ->create();
    }
}
