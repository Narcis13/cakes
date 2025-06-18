<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NavbarItemsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\NavbarItemsTable Test Case
 */
class NavbarItemsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\NavbarItemsTable
     */
    protected $NavbarItems;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.NavbarItems',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('NavbarItems') ? [] : ['className' => NavbarItemsTable::class];
        $this->NavbarItems = $this->getTableLocator()->get('NavbarItems', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->NavbarItems);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\NavbarItemsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\NavbarItemsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findActiveWithChildren method
     *
     * @return void
     * @uses \App\Model\Table\NavbarItemsTable::findActiveWithChildren()
     */
    public function testFindActiveWithChildren(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findParents method
     *
     * @return void
     * @uses \App\Model\Table\NavbarItemsTable::findParents()
     */
    public function testFindParents(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test findChildren method
     *
     * @return void
     * @uses \App\Model\Table\NavbarItemsTable::findChildren()
     */
    public function testFindChildren(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
