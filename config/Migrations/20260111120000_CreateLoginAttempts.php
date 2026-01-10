<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateLoginAttempts extends BaseMigration
{
    /**
     * Change Method.
     *
     * Creates the login_attempts table for tracking login attempts
     * and implementing rate limiting.
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('login_attempts');
        $table->addColumn('email', 'string', ['limit' => 255])
            ->addColumn('ip_address', 'string', ['limit' => 45])
            ->addColumn('user_agent', 'text', ['null' => true])
            ->addColumn('success', 'boolean', ['default' => false])
            ->addColumn('attempted_at', 'datetime')
            ->addIndex(['email', 'attempted_at'], ['name' => 'idx_email_attempted'])
            ->addIndex(['ip_address', 'attempted_at'], ['name' => 'idx_ip_attempted'])
            ->create();
    }
}
