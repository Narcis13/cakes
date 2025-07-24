<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddReminderFieldsToAppointments extends BaseMigration
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
        $table = $this->table('appointments');
        
        $table->addColumn('reminded_24h', 'datetime', [
            'default' => null,
            'null' => true,
            'comment' => 'Timestamp when 24-hour reminder was sent'
        ])
        ->addColumn('reminded_2h', 'datetime', [
            'default' => null,
            'null' => true,
            'comment' => 'Timestamp when 2-hour reminder was sent'
        ])
        ->update();
    }
}
