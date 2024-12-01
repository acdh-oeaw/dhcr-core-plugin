<?php

namespace DhcrCore\Test\TestCase\Model\Table;

use DhcrCore\Model\Table\CourseTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class CourseTypesTableTest extends TestCase
{
    public $CourseTypes;
    public $fixtures = [
        'plugin.DhcrCore.CourseTypes',
        'plugin.DhcrCore.CourseParentTypes',
        'plugin.DhcrCore.Courses'
    ];

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CourseTypes') ? [] : ['className' => CourseTypesTable::class];
        $this->CourseTypes = TableRegistry::getTableLocator()->get('CourseTypes', $config);
    }

    public function tearDown(): void
    {
        unset($this->CourseTypes);
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

    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testGetCleanQuery()
    {
        $query = [
            'foo' => 'bar',
            'sort_count' => ''
        ];
        $query = $this->CourseTypes->getCleanQuery($query);
        $this->assertArrayNotHasKey('foo', $query);
        $this->assertArrayHasKey('sort_count', $query);
    }

    public function testGetFilter()
    {
        $this->CourseTypes->query = [
            'sort_count' => ''
        ];
        $query = $this->CourseTypes->getFilter();
        $this->assertArrayHasKey('sort_count', $query);
        $this->assertTrue($query['sort_count']);
        $this->assertArrayHasKey('course_count', $query);
        $this->assertTrue($query['course_count']);

        $this->CourseTypes->query = ['count_recent' => true];
        $this->CourseTypes->getFilter();
        $this->assertTrue($this->CourseTypes->query['count_recent']);
        $this->assertTrue($this->CourseTypes->query['course_count']);
    }

    public function testGetCourseType()
    {
        $record = $this->CourseTypes->getCourseType(1);
        $this->assertArrayHasKey('id', $record);
        $this->assertArrayHasKey('name', $record);
        $this->assertArrayHasKey('course_count', $record);
        $this->assertArrayHasKey('full_name', $record);
    }

    public function testGetCourseTypes()
    {
        $this->CourseTypes->query = [];
        $records = $this->CourseTypes->getCourseTypes();
        foreach ($records as $record) {
            $this->assertArrayHasKey('id', $record);
            $this->assertArrayHasKey('name', $record);
            $this->assertArrayHasKey('full_name', $record);
            // we're dealing with an object here
            $this->assertObjectNotHasAttribute('course_count', $record);
        }
        $this->CourseTypes->query = ['course_count' => true];
        $records = $this->CourseTypes->getCourseTypes();
        foreach ($records as $record) {
            $this->assertNotEmpty($record['course_count']);
        }
        $this->CourseTypes->query = ['sort_count' => true];
        $records = $this->CourseTypes->getCourseTypes();
        $last = null;
        foreach ($records as $record) {
            if ($last !== null)
                $this->assertTrue($last > $record['course_count']);
            $last = $record['course_count'];
        }
    }
}
