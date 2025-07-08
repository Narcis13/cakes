<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WorkflowExecutionLogsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WorkflowExecutionLogsTable Test Case
 */
class WorkflowExecutionLogsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\WorkflowExecutionLogsTable
     */
    protected $WorkflowExecutionLogs;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.WorkflowExecutionLogs',
        'app.Executions',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('WorkflowExecutionLogs') ? [] : ['className' => WorkflowExecutionLogsTable::class];
        $this->WorkflowExecutionLogs = $this->getTableLocator()->get('WorkflowExecutionLogs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->WorkflowExecutionLogs);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\WorkflowExecutionLogsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\WorkflowExecutionLogsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
