<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateWorkflowPermissions extends BaseMigration
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
        $table = $this->table('workflow_permissions');
        $table->addColumn('workflow_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('user_id', 'integer', [
            'null' => true,
            'comment' => 'Specific user permission',
        ])
        ->addColumn('role', 'string', [
            'limit' => 50,
            'null' => true,
            'comment' => 'Role-based permission',
        ])
        ->addColumn('can_execute', 'boolean', [
            'default' => false,
            'null' => false,
            'comment' => 'Can start workflow execution',
        ])
        ->addColumn('can_edit', 'boolean', [
            'default' => false,
            'null' => false,
            'comment' => 'Can edit workflow definition',
        ])
        ->addColumn('can_delete', 'boolean', [
            'default' => false,
            'null' => false,
            'comment' => 'Can delete workflow',
        ])
        ->addColumn('can_view_logs', 'boolean', [
            'default' => false,
            'null' => false,
            'comment' => 'Can view execution logs',
        ])
        ->addColumn('can_manage_permissions', 'boolean', [
            'default' => false,
            'null' => false,
            'comment' => 'Can manage workflow permissions',
        ])
        ->addColumn('created', 'datetime', [
            'null' => false,
        ])
        ->addColumn('modified', 'datetime', [
            'null' => false,
        ])
        ->addIndex(['workflow_id'])
        ->addIndex(['user_id'])
        ->addIndex(['role'])
        ->addIndex(['workflow_id', 'user_id'], ['unique' => true])
        ->addIndex(['workflow_id', 'role'], ['unique' => true])
        ->addForeignKey('workflow_id', 'workflows', 'id', [
            'delete' => 'CASCADE',
            'update' => 'CASCADE',
        ])
        ->addForeignKey('user_id', 'users', 'id', [
            'delete' => 'CASCADE',
            'update' => 'CASCADE',
        ])
        ->create();
    }
}
