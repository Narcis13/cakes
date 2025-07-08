<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateWorkflowHumanTasks extends BaseMigration
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
        $table = $this->table('workflow_human_tasks');
        $table->addColumn('execution_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('node_name', 'string', [
            'limit' => 100,
            'null' => false,
            'comment' => 'Node that created this task',
        ])
        ->addColumn('title', 'string', [
            'limit' => 200,
            'null' => false,
            'comment' => 'Human-readable task title',
        ])
        ->addColumn('description', 'text', [
            'null' => true,
            'comment' => 'Detailed task description',
        ])
        ->addColumn('form_schema_json', 'text', [
            'null' => true,
            'comment' => 'JSON Schema for the form',
        ])
        ->addColumn('context_data_json', 'text', [
            'null' => true,
            'comment' => 'Context data to display to user',
        ])
        ->addColumn('assigned_to', 'integer', [
            'null' => true,
            'comment' => 'User assigned to complete this task',
        ])
        ->addColumn('assigned_role', 'string', [
            'limit' => 50,
            'null' => true,
            'comment' => 'Role that can complete this task',
        ])
        ->addColumn('priority', 'enum', [
            'values' => ['low', 'medium', 'high', 'urgent'],
            'default' => 'medium',
            'null' => false,
        ])
        ->addColumn('status', 'enum', [
            'values' => ['pending', 'assigned', 'in_progress', 'completed', 'cancelled', 'expired'],
            'default' => 'pending',
            'null' => false,
        ])
        ->addColumn('response_data_json', 'text', [
            'null' => true,
            'comment' => 'User response data',
        ])
        ->addColumn('completed_by', 'integer', [
            'null' => true,
            'comment' => 'User who completed the task',
        ])
        ->addColumn('due_at', 'datetime', [
            'null' => true,
            'comment' => 'Task deadline',
        ])
        ->addColumn('created', 'datetime', [
            'null' => false,
        ])
        ->addColumn('assigned_at', 'datetime', [
            'null' => true,
        ])
        ->addColumn('completed_at', 'datetime', [
            'null' => true,
        ])
        ->addIndex(['execution_id'])
        ->addIndex(['assigned_to'])
        ->addIndex(['assigned_role'])
        ->addIndex(['status'])
        ->addIndex(['priority'])
        ->addIndex(['due_at'])
        ->addForeignKey('execution_id', 'workflow_executions', 'id', [
            'delete' => 'CASCADE',
            'update' => 'CASCADE',
        ])
        ->addForeignKey('assigned_to', 'users', 'id', [
            'delete' => 'SET_NULL',
            'update' => 'CASCADE',
        ])
        ->addForeignKey('completed_by', 'users', 'id', [
            'delete' => 'SET_NULL',
            'update' => 'CASCADE',
        ])
        ->create();
    }
}
