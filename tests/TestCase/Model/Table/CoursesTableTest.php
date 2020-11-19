<?php
namespace DhcrCore\Test\TestCase\Model\Table;

use DhcrCore\Model\Table\CoursesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CoursesTable Test Case
 */
class CoursesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CoursesTable
     */
    public $Courses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.DhcrCore.Courses',
        'plugin.DhcrCore.Users',
        'plugin.DhcrCore.DeletionReasons',
        'plugin.DhcrCore.Countries',
        'plugin.DhcrCore.Cities',
        'plugin.DhcrCore.Institutions',
        'plugin.DhcrCore.CourseParentTypes',
        'plugin.DhcrCore.CourseTypes',
        'plugin.DhcrCore.Languages',
        'plugin.DhcrCore.CourseDurationUnits',
        'plugin.DhcrCore.Disciplines',
        'plugin.DhcrCore.TadirahObjects',
        'plugin.DhcrCore.TadirahTechniques',
		'plugin.DhcrCore.CoursesTadirahObjects',
		'plugin.DhcrCore.CoursesTadirahTechniques',
		'plugin.DhcrCore.CoursesDisciplines'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Courses') ? [] : ['className' => CoursesTable::class];
        $this->Courses = TableRegistry::getTableLocator()->get('Courses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Courses);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }


    public function testGetCleanQuery() {
		$query = [
			'foo' => 'bar',
			'discipline_id' => '1,2, 3 , 4'
		];
		$query = $this->Courses->getCleanQuery($query);
		$this->assertArrayNotHasKey('foo', $query);
		$this->assertArrayHasKey('discipline_id', $query);
		$this->assertTrue(is_array($query['discipline_id']));
	}


    public function testGetFilter() {
		$this->Courses->query = [];
		// set some values for testing
		foreach($this->Courses->allowedFilters as $key) {
			switch($key) {
				case 'recent':
                    $this->Courses->query[$key] = ''; break;    // should evaluate true
                case 'online':
                case 'recurring':
                    $this->Courses->query[$key] = ''; break;    // should not be evaluated
				default:
					// should be some numeric value of a foreign key, delivered as string!
					$this->Courses->query[$key] = '3';
			}
		}
    	$conditions = $this->Courses->getFilter();

    	$this->assertArrayHasKey('Courses.active', $conditions);
		$this->assertEquals($conditions['Courses.active'], true);

    	foreach($this->Courses->allowedFilters as $key) {
    		switch($key) {
				case 'recent':
					$this->assertEquals($this->Courses->query['recent'], true);	// empty param 'recent' defaults to true
					$this->assertArrayHasKey('Courses.updated >', $conditions);
					$this->assertFalse($conditions['Courses.deleted']);
					// no test for date
					break;
                case 'online':
                    $this->assertArrayNotHasKey('Courses.online_course', $conditions);
                    break;
                case 'recurring':
                    $this->assertArrayNotHasKey('Courses.recurring', $conditions);
                    break;
                case 'start_date':
					$this->assertArrayHasKey('Courses.created >=', $conditions);
					break;
				case 'end_date':
					$this->assertArrayHasKey('Courses.updated <=', $conditions);
					break;
				case 'sort':
					// this should not go into the conditions array!
					$this->assertArrayNotHasKey('Courses.sort', $conditions);
					break;
    			default:
					$this->assertArrayHasKey('Courses.'.$key, $conditions);
					$this->assertEquals($conditions['Courses.'.$key], 3);
			}
		}

    	// tests for online/campus presence - 1,'1',true | 0,'0',false
        $this->Courses->query = [];
        $this->Courses->query['online'] = 1;
        $conditions = $this->Courses->getFilter();
        $this->assertArrayHasKey('Courses.online_course', $conditions);
        $this->assertTrue($conditions['Courses.online_course']);

        $this->Courses->query = [];
        $this->Courses->query['online'] = 0;
        $conditions = $this->Courses->getFilter();
        $this->assertArrayHasKey('Courses.online_course', $conditions);
        $this->assertFalse($conditions['Courses.online_course']);

        $this->Courses->query = [];
        $this->Courses->query['online'] = 'TRUE';
        $conditions = $this->Courses->getFilter();
        $this->assertArrayHasKey('Courses.online_course', $conditions);
        $this->assertTrue($conditions['Courses.online_course']);

        $this->Courses->query = [];
        $this->Courses->query['online'] = 'FALSE';
        $conditions = $this->Courses->getFilter();
        $this->assertArrayHasKey('Courses.online_course', $conditions);
        $this->assertFalse($conditions['Courses.online_course']);

        $this->Courses->query = [];
        $this->Courses->query['online'] = '1';
        $conditions = $this->Courses->getFilter();
        $this->assertArrayHasKey('Courses.online_course', $conditions);
        $this->assertTrue($conditions['Courses.online_course']);

        $this->Courses->query = [];
        $this->Courses->query['online'] = '0';
        $conditions = $this->Courses->getFilter();
        $this->assertArrayHasKey('Courses.online_course', $conditions);
        $this->assertFalse($conditions['Courses.online_course']);

        $this->Courses->query = [];
        $this->Courses->query['online'] = true;
        $conditions = $this->Courses->getFilter();
        $this->assertArrayHasKey('Courses.online_course', $conditions);
        $this->assertTrue($conditions['Courses.online_course']);

        $this->Courses->query = [];
        $this->Courses->query['online'] = false;
        $conditions = $this->Courses->getFilter();
        $this->assertArrayHasKey('Courses.online_course', $conditions);
        $this->assertFalse($conditions['Courses.online_course']);

        // tests for recurring parameter, same as online_course
        $this->Courses->query = [];
        $this->Courses->query['recurring'] = 1;
        $conditions = $this->Courses->getFilter();
        $this->assertArrayHasKey('Courses.recurring', $conditions);
        $this->assertTrue($conditions['Courses.recurring']);

        $this->Courses->query = [];
        $this->Courses->query['recurring'] = 0;
        $conditions = $this->Courses->getFilter();
        $this->assertArrayHasKey('Courses.recurring', $conditions);
        $this->assertFalse($conditions['Courses.recurring']);

        $this->Courses->query = [];
        $this->Courses->query['recurring'] = 'TRUE';
        $conditions = $this->Courses->getFilter();
        $this->assertArrayHasKey('Courses.recurring', $conditions);
        $this->assertTrue($conditions['Courses.recurring']);

        $this->Courses->query = [];
        $this->Courses->query['recurring'] = 'FALSE';
        $conditions = $this->Courses->getFilter();
        $this->assertArrayHasKey('Courses.recurring', $conditions);
        $this->assertFalse($conditions['Courses.recurring']);

        $this->Courses->query = [];
        $this->Courses->query['recurring'] = '1';
        $conditions = $this->Courses->getFilter();
        $this->assertArrayHasKey('Courses.recurring', $conditions);
        $this->assertTrue($conditions['Courses.recurring']);

        $this->Courses->query = [];
        $this->Courses->query['recurring'] = '0';
        $conditions = $this->Courses->getFilter();
        $this->assertArrayHasKey('Courses.recurring', $conditions);
        $this->assertFalse($conditions['Courses.recurring']);

        $this->Courses->query = [];
        $this->Courses->query['recurring'] = true;
        $conditions = $this->Courses->getFilter();
        $this->assertArrayHasKey('Courses.recurring', $conditions);
        $this->assertTrue($conditions['Courses.recurring']);

        $this->Courses->query = [];
        $this->Courses->query['recurring'] = false;
        $conditions = $this->Courses->getFilter();
        $this->assertArrayHasKey('Courses.recurring', $conditions);
        $this->assertFalse($conditions['Courses.recurring']);
	}


	public function testGetJoins() {
		$this->Courses->query = [
			'discipline_id' => [],
			'tadirah_technique_id' => [3,4],
			'tadirah_object_id' => [2]
		];
		$joins = $this->Courses->getJoins();
		foreach($joins as $join) {
			$this->assertArrayHasKey('assoc', $join);
			$this->assertArrayHasKey('conditions', $join);
		}
	}


	public function testGetSorters() {
    	$this->Courses->query = [
    		'sort' => ['name','Cities.name:desc','Cities.foo']
		];
    	$sorters = $this->Courses->getSorters();
    	// allowed sort criteria should match existing fields from associations involved in the query
		// if no model is given, the default model 'Courses' should be assumed
		// ASC is the default sort direction
    	$this->assertArrayHasKey('Courses.name', $sorters);
    	$this->assertEquals('ASC', $sorters['Courses.name']);
    	$this->assertArrayHasKey('Cities.name', $sorters);
    	$this->assertEquals('DESC', $sorters['Cities.name']);
    	$this->assertArrayNotHasKey('Cities.foo', $sorters);
	}


	public function testGetResults() {
 		$query = [
 			'country_id' => '1',
			'discipline_id' => [1,2]
		];
    	$this->Courses->evaluateQuery($query);
    	$courses = $this->Courses->getResults();

 		$this->assertNotEmpty($courses);

		$query = [
			'country_id' => '2',
			'discipline_id' => [1,2]
		];
		$this->Courses->evaluateQuery($query);
		$courses = $this->Courses->getResults();

		$this->assertEmpty($courses);

		$query = [
			'country_id' => '1',
			'discipline_id' => [3,2]
		];
		$this->Courses->evaluateQuery($query);
		$courses = $this->Courses->getResults();

		$this->assertEmpty($courses);
	}


	public function testCountResults() {
		$query = [
			'country_id' => '1',
			'discipline_id' => [1,2]
		];
		$this->Courses->evaluateQuery($query);
		$result = $this->Courses->countResults();

		$this->assertEquals(1, $result);
	}
}
