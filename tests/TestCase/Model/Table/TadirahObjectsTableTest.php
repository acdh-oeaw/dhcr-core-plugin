<?php

namespace DhcrCore\Test\TestCase\Model\Table;

use DhcrCore\Model\Table\TadirahObjectsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class TadirahObjectsTableTest extends TestCase
{
    public $TadirahObjects;
    public $fixtures = [
        'plugin.DhcrCore.TadirahObjects',
        'plugin.DhcrCore.Courses',
        'plugin.DhcrCore.CoursesTadirahObjects'
    ];

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('TadirahObjects') ? [] : ['className' => TadirahObjectsTable::class];
        $this->TadirahObjects = TableRegistry::getTableLocator()->get('TadirahObjects', $config);
    }

    public function tearDown(): void
    {
        unset($this->TadirahObjects);
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

    public function testGetCleanQuery()
    {
        $query = [
            'foo' => 'bar',
            'sort_count' => ''
        ];
        $query = $this->TadirahObjects->getCleanQuery($query);
        $this->assertArrayNotHasKey('foo', $query);
        $this->assertArrayHasKey('sort_count', $query);
    }

    public function testGetFilter()
    {
        $this->TadirahObjects->query = [
            'sort_count' => ''
        ];
        $query = $this->TadirahObjects->getFilter();
        $this->assertArrayHasKey('sort_count', $query);
        $this->assertTrue($query['sort_count']);
        $this->assertArrayHasKey('course_count', $query);
        $this->assertTrue($query['course_count']);

        $this->TadirahObjects->query = ['count_recent' => true];
        $this->TadirahObjects->getFilter();
        $this->assertTrue($this->TadirahObjects->query['count_recent']);
        $this->assertTrue($this->TadirahObjects->query['course_count']);
    }

    public function testGetTadirahObject()
    {
        $record = $this->TadirahObjects->getTadirahObject(1);
        $this->assertArrayHasKey('id', $record);
        $this->assertArrayHasKey('name', $record);
        $this->assertArrayHasKey('course_count', $record);
    }

    public function testGetTadirahObjects()
    {
        $this->TadirahObjects->query = [];
        $records = $this->TadirahObjects->getTadirahObjects();
        foreach ($records as $record) {
            $this->assertArrayHasKey('id', $record);
            $this->assertArrayHasKey('name', $record);
            // we're dealing with an object here
            $this->assertObjectNotHasAttribute('course_count', $record);
        }
        $this->TadirahObjects->query = ['course_count' => true];
        $records = $this->TadirahObjects->getTadirahObjects();
        foreach ($records as $record) {
            $this->assertNotEmpty($record['course_count']);
        }
        $this->TadirahObjects->query = ['sort_count' => true];
        $records = $this->TadirahObjects->getTadirahObjects();
        $last = null;
        foreach ($records as $record) {
            if ($last !== null)
                $this->assertTrue($last > $record['course_count']);
            $last = $record['course_count'];
        }
    }
}
