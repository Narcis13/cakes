<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateWorkflowExecutionLogs extends BaseMigration
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
        $table = $this->table('workflow_execution_logs');
        $table->addColumn('execution_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('node_name', 'string', [
            'limit' => 100,
            'null' => false,
            'comment' => 'Node that was executed',
        ])
        ->addColumn('node_type', 'string', [
            'limit' => 50,
            'null' => true,
            'comment' => 'Type of node executed',
        ])
        ->addColumn('edge_taken', 'string', [
            'limit' => 100,
            'null' => true,
            'comment' => 'Which edge was taken from this node',
        ])
        ->addColumn('level', 'enum', [
            'values' => ['debug', 'info', 'warning', 'error'],
            'default' => 'info',
            'null' => false,
        ])
        ->addColumn('message', 'text', [
            'null' => true,
            'comment' => 'Log message',
        ])
        ->addColumn('data_json', 'text', [
            'null' => true,
            'comment' => 'Additional data returned by the node',
        ])
        ->addColumn('state_snapshot', 'text', [
            'null' => true,
            'comment' => 'State snapshot after node execution',
        ])
        ->addColumn('execution_time', 'integer', [
            'null' => true,
            'comment' => 'Node execution time in milliseconds',
        ])
        ->addColumn('created', 'datetime', [
            'null' => false,
        ])
        ->addIndex(['execution_id'])
        ->addIndex(['node_name'])
        ->addIndex(['level'])
        ->addIndex(['created'])
        ->addForeignKey('execution_id', 'workflow_executions', 'id', [
            'delete' => 'CASCADE',
            'update' => 'CASCADE',
        ])
        ->create();
    }
}
