<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateAuditLogs extends BaseMigration
{
    /**
     * Change Method.
     *
     * Creates the audit_logs table for tracking admin actions
     * on sensitive data for audit trail purposes.
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('audit_logs');
        $table->addColumn('user_id', 'integer', ['null' => true])
            ->addColumn('action', 'string', ['limit' => 50])
            ->addColumn('model', 'string', ['limit' => 100])
            ->addColumn('record_id', 'integer', ['null' => true])
            ->addColumn('old_values', 'text', ['null' => true])
            ->addColumn('new_values', 'text', ['null' => true])
            ->addColumn('ip_address', 'string', ['limit' => 45, 'null' => true])
            ->addColumn('user_agent', 'text', ['null' => true])
            ->addColumn('created', 'datetime')
            ->addIndex(['user_id'], ['name' => 'idx_user_id'])
            ->addIndex(['model', 'record_id'], ['name' => 'idx_model_record'])
            ->addIndex(['created'], ['name' => 'idx_created'])
            ->create();
    }
}
