<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateFiles extends BaseMigration
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
        $table = $this->table('files');
        $table->addColumn('filename', 'string', ['limit' => 255])
              ->addColumn('original_name', 'string', ['limit' => 255])
              ->addColumn('file_path', 'string', ['limit' => 500])
              ->addColumn('file_url', 'string', ['limit' => 500])
              ->addColumn('mime_type', 'string', ['limit' => 100])
              ->addColumn('file_size', 'integer')
              ->addColumn('file_type', 'string', ['limit' => 50]) // pdf, doc, txt, etc.
              ->addColumn('description', 'text', ['null' => true])
              ->addColumn('category', 'string', ['limit' => 100, 'null' => true])
              ->addColumn('is_public', 'boolean', ['default' => true])
              ->addColumn('download_count', 'integer', ['default' => 0])
              ->addColumn('uploaded_by', 'integer', ['null' => true])
              ->addColumn('created', 'datetime')
              ->addColumn('modified', 'datetime')
              ->addForeignKey('uploaded_by', 'users', 'id', ['delete' => 'SET_NULL'])
              ->addIndex(['file_type'])
              ->addIndex(['category'])
              ->addIndex(['is_public'])
              ->addIndex(['created'])
              ->create();
    }
}
