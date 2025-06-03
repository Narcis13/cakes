<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreatePages extends BaseMigration
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
        $table = $this->table('pages');
        $table->addColumn('title', 'string', ['limit' => 200])
              ->addColumn('slug', 'string', ['limit' => 200])
              ->addColumn('content', 'text')
              ->addColumn('meta_description', 'string', ['limit' => 160, 'null' => true])
              ->addColumn('template', 'string', ['limit' => 50, 'null' => true])
              ->addColumn('is_published', 'boolean', ['default' => true])
              ->addColumn('created', 'datetime')
              ->addColumn('modified', 'datetime')
              ->addIndex(['slug'], ['unique' => true])
              ->addIndex(['is_published'])
              ->create();
    }
}
