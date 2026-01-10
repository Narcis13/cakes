<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddLockoutFieldsToUsers extends BaseMigration
{
    /**
     * Change Method.
     *
     * Adds lockout and login tracking fields to the users table.
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('users');
        $table->addColumn('failed_login_attempts', 'integer', [
                'default' => 0,
                'null' => false,
                'after' => 'password',
            ])
            ->addColumn('locked_until', 'datetime', [
                'null' => true,
                'after' => 'failed_login_attempts',
            ])
            ->addColumn('last_login_at', 'datetime', [
                'null' => true,
                'after' => 'locked_until',
            ])
            ->addColumn('last_login_ip', 'string', [
                'limit' => 45,
                'null' => true,
                'after' => 'last_login_at',
            ])
            ->update();
    }
}
