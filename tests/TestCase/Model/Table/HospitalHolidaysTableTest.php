<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HospitalHolidaysTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HospitalHolidaysTable Test Case
 */
class HospitalHolidaysTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\HospitalHolidaysTable
     */
    protected $HospitalHolidays;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.HospitalHolidays',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('HospitalHolidays') ? [] : ['className' => HospitalHolidaysTable::class];
        $this->HospitalHolidays = $this->getTableLocator()->get('HospitalHolidays', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->HospitalHolidays);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\HospitalHolidaysTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\HospitalHolidaysTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
