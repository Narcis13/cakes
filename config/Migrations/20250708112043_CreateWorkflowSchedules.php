<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateWorkflowSchedules extends BaseMigration
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
        $table = $this->table('workflow_schedules');
        $table->addColumn('workflow_id', 'integer', [
            'null' => false,
        ])
        ->addColumn('name', 'string', [
            'limit' => 200,
            'null' => false,
            'comment' => 'Schedule name',
        ])
        ->addColumn('description', 'text', [
            'null' => true,
        ])
        ->addColumn('cron_expression', 'string', [
            'limit' => 100,
            'null' => true,
            'comment' => 'Cron expression for scheduling',
        ])
        ->addColumn('schedule_type', 'enum', [
            'values' => ['cron', 'interval', 'once'],
            'default' => 'cron',
            'null' => false,
        ])
        ->addColumn('interval_minutes', 'integer', [
            'null' => true,
            'comment' => 'Interval in minutes for interval type',
        ])
        ->addColumn('run_at', 'datetime', [
            'null' => true,
            'comment' => 'Specific time for once type',
        ])
        ->addColumn('input_data_json', 'text', [
            'null' => true,
            'comment' => 'Input data for workflow execution',
        ])
        ->addColumn('timezone', 'string', [
            'limit' => 50,
            'default' => 'UTC',
            'null' => false,
        ])
        ->addColumn('is_active', 'boolean', [
            'default' => true,
            'null' => false,
        ])
        ->addColumn('last_run_at', 'datetime', [
            'null' => true,
        ])
        ->addColumn('last_execution_id', 'integer', [
            'null' => true,
            'comment' => 'Last execution created by this schedule',
        ])
        ->addColumn('next_run_at', 'datetime', [
            'null' => true,
        ])
        ->addColumn('run_count', 'integer', [
            'default' => 0,
            'null' => false,
        ])
        ->addColumn('max_runs', 'integer', [
            'null' => true,
            'comment' => 'Maximum number of runs (null = unlimited)',
        ])
        ->addColumn('created_by', 'integer', [
            'null' => false,
        ])
        ->addColumn('created', 'datetime', [
            'null' => false,
        ])
        ->addColumn('modified', 'datetime', [
            'null' => false,
        ])
        ->addIndex(['workflow_id'])
        ->addIndex(['is_active'])
        ->addIndex(['next_run_at'])
        ->addIndex(['created_by'])
        ->addForeignKey('workflow_id', 'workflows', 'id', [
            'delete' => 'CASCADE',
            'update' => 'CASCADE',
        ])
        ->addForeignKey('last_execution_id', 'workflow_executions', 'id', [
            'delete' => 'SET_NULL',
            'update' => 'CASCADE',
        ])
        ->addForeignKey('created_by', 'users', 'id', [
            'delete' => 'RESTRICT',
            'update' => 'CASCADE',
        ])
        ->create();
    }
}
