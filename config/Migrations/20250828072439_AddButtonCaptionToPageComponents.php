<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddButtonCaptionToPageComponents extends BaseMigration
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
        $table->addColumn('button_caption', 'string', ['limit' => 100, 'null' => true, 'after' => 'url'])
              ->update();
    }
}
