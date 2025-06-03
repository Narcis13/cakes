<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateNews extends BaseMigration
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
        $table = $this->table('news');
        $table->addColumn('title', 'string', ['limit' => 200])
              ->addColumn('slug', 'string', ['limit' => 200])
              ->addColumn('content', 'text')
              ->addColumn('excerpt', 'text', ['null' => true])
              ->addColumn('author_id', 'integer', ['null' => true])
              ->addColumn('category_id', 'integer', ['null' => true])
              ->addColumn('featured_image', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('is_published', 'boolean', ['default' => false])
              ->addColumn('publish_date', 'datetime', ['null' => true])
              ->addColumn('views_count', 'integer', ['default' => 0])
              ->addColumn('created', 'datetime')
              ->addColumn('modified', 'datetime')
              ->addForeignKey('author_id', 'staff', 'id', ['delete' => 'SET_NULL'])
              ->addForeignKey('category_id', 'news_categories', 'id', ['delete' => 'SET_NULL'])
              ->addIndex(['slug'], ['unique' => true])
              ->addIndex(['is_published'])
              ->addIndex(['publish_date'])
              ->create();
    }
}
