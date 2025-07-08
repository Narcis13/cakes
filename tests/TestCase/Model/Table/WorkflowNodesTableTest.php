<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WorkflowNodesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WorkflowNodesTable Test Case
 */
class WorkflowNodesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\WorkflowNodesTable
     */
    protected $WorkflowNodes;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.WorkflowNodes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('WorkflowNodes') ? [] : ['className' => WorkflowNodesTable::class];
        $this->WorkflowNodes = $this->getTableLocator()->get('WorkflowNodes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->WorkflowNodes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\WorkflowNodesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\WorkflowNodesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
