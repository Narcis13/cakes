<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateWorkflowExecutions extends BaseMigration
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
        $table = $this->table('workflow_executions');
        $table->addColumn('workflow_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('status', 'enum', [
            'values' => ['running', 'paused', 'completed', 'failed', 'cancelled'],
            'default' => 'running',
            'null' => false,
        ])
        ->addColumn('current_node', 'string', [
            'limit' => 100,
            'null' => true,
            'comment' => 'Current node being executed',
        ])
        ->addColumn('current_position', 'text', [
            'null' => true,
            'comment' => 'JSON array tracking position in nested structures',
        ])
        ->addColumn('state_json', 'text', [
            'null' => false,
            'comment' => 'Current execution state',
        ])
        ->addColumn('input_data', 'text', [
            'null' => true,
            'comment' => 'Initial input data for the workflow',
        ])
        ->addColumn('output_data', 'text', [
            'null' => true,
            'comment' => 'Final output data from the workflow',
        ])
        ->addColumn('error_message', 'text', [
            'null' => true,
            'comment' => 'Error message if execution failed',
        ])
        ->addColumn('started_by', 'integer', [
            'null' => false,
            'comment' => 'User who started the execution',
        ])
        ->addColumn('started_at', 'datetime', [
            'null' => false,
        ])
        ->addColumn('paused_at', 'datetime', [
            'null' => true,
        ])
        ->addColumn('completed_at', 'datetime', [
            'null' => true,
        ])
        ->addColumn('execution_time', 'integer', [
            'null' => true,
            'comment' => 'Total execution time in milliseconds',
        ])
        ->addIndex(['workflow_id'])
        ->addIndex(['status'])
        ->addIndex(['started_by'])
        ->addIndex(['started_at'])
        ->addForeignKey('workflow_id', 'workflows', 'id', [
            'delete' => 'RESTRICT',
            'update' => 'CASCADE',
        ])
        ->addForeignKey('started_by', 'users', 'id', [
            'delete' => 'RESTRICT',
            'update' => 'CASCADE',
        ])
        ->create();
    }
}
