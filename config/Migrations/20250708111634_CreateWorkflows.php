<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateWorkflows extends BaseMigration
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
        $table = $this->table('workflows');
        $table->addColumn('name', 'string', [
            'limit' => 200,
            'null' => false,
        ])
        ->addColumn('description', 'text', [
            'null' => true,
        ])
        ->addColumn('definition_json', 'text', [
            'null' => false,
            'comment' => 'JSON workflow definition following FlowScript specification',
        ])
        ->addColumn('version', 'integer', [
            'default' => 1,
            'null' => false,
        ])
        ->addColumn('status', 'enum', [
            'values' => ['draft', 'active', 'inactive', 'archived'],
            'default' => 'draft',
            'null' => false,
        ])
        ->addColumn('category', 'string', [
            'limit' => 100,
            'null' => true,
            'comment' => 'Workflow category for organization',
        ])
        ->addColumn('icon', 'string', [
            'limit' => 50,
            'null' => true,
            'comment' => 'Font Awesome icon class',
        ])
        ->addColumn('created_by', 'integer', [
            'null' => false,
            'comment' => 'User ID who created the workflow',
        ])
        ->addColumn('is_template', 'boolean', [
            'default' => false,
            'null' => false,
            'comment' => 'Whether this workflow can be used as a template',
        ])
        ->addColumn('created', 'datetime', [
            'null' => false,
        ])
        ->addColumn('modified', 'datetime', [
            'null' => false,
        ])
        ->addIndex(['name'])
        ->addIndex(['status'])
        ->addIndex(['category'])
        ->addIndex(['created_by'])
        ->addForeignKey('created_by', 'users', 'id', [
            'delete' => 'RESTRICT',
            'update' => 'CASCADE',
        ])
        ->create();
    }
}
