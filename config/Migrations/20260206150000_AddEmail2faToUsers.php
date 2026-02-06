<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddEmail2faToUsers extends BaseMigration
{
    /**
     * Change Method.
     *
     * Adds email2FA column to users table for two-factor authentication via email.
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('users');
        $table->addColumn('email2FA', 'string', [
                'limit' => 255,
                'null' => true,
                'default' => null,
                'after' => 'role',
            ])
            ->update();
    }
}
