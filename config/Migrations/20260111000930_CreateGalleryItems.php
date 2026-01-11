<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateGalleryItems extends BaseMigration
{
    /**
     * Change Method.
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('gallery_items');
        $table->addColumn('image_url', 'string', ['limit' => 500])
              ->addColumn('title', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('alt_text', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('sort_order', 'integer', ['default' => 0])
              ->addColumn('is_active', 'boolean', ['default' => true])
              ->addColumn('created', 'datetime')
              ->addColumn('modified', 'datetime')
              ->addIndex(['sort_order'])
              ->addIndex(['is_active'])
              ->create();
    }
}
