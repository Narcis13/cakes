<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WorkflowsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WorkflowsTable Test Case
 */
class WorkflowsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\WorkflowsTable
     */
    protected $Workflows;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Workflows',
        'app.WorkflowExecutions',
        'app.WorkflowPermissions',
        'app.WorkflowSchedules',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Workflows') ? [] : ['className' => WorkflowsTable::class];
        $this->Workflows = $this->getTableLocator()->get('Workflows', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Workflows);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\WorkflowsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
