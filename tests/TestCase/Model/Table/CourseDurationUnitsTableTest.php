<?php

namespace DhcrCore\Test\TestCase\Model\Table;

use DhcrCore\Model\Table\CourseDurationUnitsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class CourseDurationUnitsTableTest extends TestCase
{
    public $CourseDurationUnits;
    public $fixtures = [
        'plugin.DhcrCore.CourseDurationUnits',
        'plugin.DhcrCore.Courses'
    ];

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CourseDurationUnits') ? [] : ['className' => CourseDurationUnitsTable::class];
        $this->CourseDurationUnits = TableRegistry::getTableLocator()->get('CourseDurationUnits', $config);
    }

    public function tearDown(): void
    {
        unset($this->CourseDurationUnits);
        parent::tearDown();
    }

    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

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
