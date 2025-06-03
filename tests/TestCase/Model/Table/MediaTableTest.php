<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MediaTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MediaTable Test Case
 */
class MediaTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MediaTable
     */
    protected $Media;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Media',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Media') ? [] : ['className' => MediaTable::class];
        $this->Media = $this->getTableLocator()->get('Media', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Media);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\MediaTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
