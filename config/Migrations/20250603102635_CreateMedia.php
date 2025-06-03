<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateMedia extends BaseMigration
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
        $table = $this->table('media');
        $table->addColumn('filename', 'string', ['limit' => 255])
              ->addColumn('original_name', 'string', ['limit' => 255])
              ->addColumn('mime_type', 'string', ['limit' => 100])
              ->addColumn('size', 'integer')
              ->addColumn('alt_text', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('created', 'datetime')
              ->addColumn('modified', 'datetime')
              ->create();
    }
}
