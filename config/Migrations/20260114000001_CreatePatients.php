<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreatePatients extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('patients');
        $table
            ->addColumn('full_name', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('email', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('phone', 'string', ['limit' => 20, 'null' => false])
            ->addColumn('password', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('email_verified_at', 'datetime', ['null' => true, 'default' => null])
            ->addColumn('verification_token', 'string', ['limit' => 64, 'null' => true, 'default' => null])
            ->addColumn('password_reset_token', 'string', ['limit' => 64, 'null' => true, 'default' => null])
            ->addColumn('password_reset_expires', 'datetime', ['null' => true, 'default' => null])
            ->addColumn('failed_login_attempts', 'integer', ['default' => 0, 'null' => false])
            ->addColumn('locked_until', 'datetime', ['null' => true, 'default' => null])
            ->addColumn('last_login_at', 'datetime', ['null' => true, 'default' => null])
            ->addColumn('last_login_ip', 'string', ['limit' => 45, 'null' => true, 'default' => null])
            ->addColumn('is_active', 'boolean', ['default' => true, 'null' => false])
            ->addColumn('created', 'datetime', ['null' => false])
            ->addColumn('modified', 'datetime', ['null' => false])
            ->addIndex(['email'], ['unique' => true, 'name' => 'idx_patients_email'])
            ->addIndex(['verification_token'], ['name' => 'idx_patients_verification_token'])
            ->addIndex(['password_reset_token'], ['name' => 'idx_patients_reset_token'])
            ->create();
    }
}
