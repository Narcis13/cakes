<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddImageTypeToPageComponents extends BaseMigration
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
        $table->addColumn('image_type', 'string', [
            'limit' => 20,
            'null' => true,
            'default' => 'url',
            'comment' => 'url or upload'
        ]);
        $table->update();
    }
}
