<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreatePageComponents extends BaseMigration
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
        $table = $this->table('page_components');
        $table->addColumn('page_id', 'integer')
              ->addColumn('type', 'string', ['limit' => 50]) // 'html', 'image', 'link'
              ->addColumn('content', 'text', ['null' => true])
              ->addColumn('title', 'string', ['limit' => 200, 'null' => true])
              ->addColumn('url', 'string', ['limit' => 500, 'null' => true])
              ->addColumn('alt_text', 'string', ['limit' => 200, 'null' => true])
              ->addColumn('css_class', 'string', ['limit' => 100, 'null' => true])
              ->addColumn('sort_order', 'integer', ['default' => 0])
              ->addColumn('is_active', 'boolean', ['default' => true])
              ->addColumn('created', 'datetime')
              ->addColumn('modified', 'datetime')
              ->addForeignKey('page_id', 'pages', 'id', ['delete' => 'CASCADE'])
              ->addIndex(['page_id'])
              ->addIndex(['type'])
              ->addIndex(['sort_order'])
              ->create();
    }
}
