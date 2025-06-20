<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PageComponentsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PageComponentsTable Test Case
 */
class PageComponentsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PageComponentsTable
     */
    protected $PageComponents;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.PageComponents',
        'app.Pages',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('PageComponents') ? [] : ['className' => PageComponentsTable::class];
        $this->PageComponents = $this->getTableLocator()->get('PageComponents', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->PageComponents);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\PageComponentsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\PageComponentsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
