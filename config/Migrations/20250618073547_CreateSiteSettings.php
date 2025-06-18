<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateSiteSettings extends BaseMigration
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
        $table = $this->table('site_settings');
        $table->addColumn('key_name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('value', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('description', 'string', [
            'default' => null,
            'limit' => 255,
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
            'key_name',
        
            ], [
            'name' => 'UNIQUE_KEY_NAME',
            'unique' => true,
        ]);
        $table->addIndex([
            'description',
        
            ], [
            'name' => 'BY_DESCRIPTION',
            'unique' => false,
        ]);
        $table->create();
    }
}
