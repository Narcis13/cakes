<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateSettings extends BaseMigration
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
        $table = $this->table('settings');
        $table->addColumn('key_name', 'string', ['limit' => 100])
              ->addColumn('value', 'text', ['null' => true])
              ->addColumn('description', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('type', 'string', ['limit' => 20, 'default' => 'text'])
              ->addColumn('created', 'datetime')
              ->addColumn('modified', 'datetime')
              ->addIndex(['key_name'], ['unique' => true])
              ->create();
    }
}
