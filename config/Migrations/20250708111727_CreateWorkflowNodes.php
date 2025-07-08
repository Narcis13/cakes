<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateWorkflowNodes extends BaseMigration
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
        $table = $this->table('workflow_nodes');
        $table->addColumn('name', 'string', [
            'limit' => 100,
            'null' => false,
            'comment' => 'Unique node identifier',
        ])
        ->addColumn('type', 'enum', [
            'values' => ['action', 'human', 'control', 'integration'],
            'null' => false,
        ])
        ->addColumn('category', 'string', [
            'limit' => 50,
            'null' => true,
            'comment' => 'Node category for organization',
        ])
        ->addColumn('description', 'text', [
            'null' => true,
        ])
        ->addColumn('metadata_json', 'text', [
            'null' => false,
            'comment' => 'Node metadata including AI hints, form schemas, etc.',
        ])
        ->addColumn('handler_class', 'string', [
            'limit' => 255,
            'null' => false,
            'comment' => 'PHP class that implements this node',
        ])
        ->addColumn('icon', 'string', [
            'limit' => 50,
            'null' => true,
            'comment' => 'Font Awesome icon class',
        ])
        ->addColumn('is_builtin', 'boolean', [
            'default' => false,
            'null' => false,
            'comment' => 'Whether this is a system-provided node',
        ])
        ->addColumn('is_active', 'boolean', [
            'default' => true,
            'null' => false,
        ])
        ->addColumn('created', 'datetime', [
            'null' => false,
        ])
        ->addColumn('modified', 'datetime', [
            'null' => false,
        ])
        ->addIndex(['name'], ['unique' => true])
        ->addIndex(['type'])
        ->addIndex(['category'])
        ->addIndex(['is_active'])
        ->create();
    }
}
