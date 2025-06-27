<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddServicesHtmlAndPictureToDepartments extends BaseMigration
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
        $table = $this->table('departments');
        $table->addColumn('services_html', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('picture', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->update();
    }
}
