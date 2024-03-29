<?php

namespace DhcrCore\Test\TestCase\Model\Table;

use DhcrCore\Model\Table\CourseDurationUnitsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CourseDurationUnitsTable Test Case
 */
class CourseDurationUnitsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CourseDurationUnitsTable
     */
    public $CourseDurationUnits;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.DhcrCore.CourseDurationUnits',
        'plugin.DhcrCore.Courses'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CourseDurationUnits') ? [] : ['className' => CourseDurationUnitsTable::class];
        $this->CourseDurationUnits = TableRegistry::getTableLocator()->get('CourseDurationUnits', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->CourseDurationUnits);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testGetCourseDurationUnit()
    {
        $record = $this->CourseDurationUnits->getCourseDurationUnit(1);
        $this->assertArrayHasKey('id', $record);
        $this->assertArrayHasKey('name', $record);
    }

    public function testGetCourseDurationUnits()
    {
        $records = $this->CourseDurationUnits->getCourseDurationUnits();
        foreach ($records as $record) {
            $this->assertArrayHasKey('id', $record);
            $this->assertArrayHasKey('name', $record);
        }
    }
}
