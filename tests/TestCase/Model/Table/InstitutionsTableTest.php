<?php

namespace DhcrCore\Test\TestCase\Model\Table;

use DhcrCore\Model\Table\InstitutionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class InstitutionsTableTest extends TestCase
{
	public $Institutions;
	public $fixtures = [
		'plugin.DhcrCore.Institutions',
		'plugin.DhcrCore.Cities',
		'plugin.DhcrCore.Countries',
		'plugin.DhcrCore.Courses',
		'plugin.DhcrCore.Users'
	];

	public function setUp(): void
	{
		parent::setUp();
		$config = TableRegistry::getTableLocator()->exists('Institutions') ? [] : ['className' => InstitutionsTable::class];
		$this->Institutions = TableRegistry::getTableLocator()->get('Institutions', $config);
	}

	public function tearDown(): void
	{
		unset($this->Institutions);
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
			'sort_count' => '',
			'group' => true,
			'country_id' => '3',
			'city_id' => '5'
		];
		$query = $this->Institutions->getCleanQuery($query);
		$this->assertArrayNotHasKey('foo', $query);
		$this->assertArrayHasKey('sort_count', $query);
		$this->assertArrayHasKey('group', $query);
		$this->assertArrayHasKey('country_id', $query);
		$this->assertArrayHasKey('city_id', $query);
	}

	public function testGetFilter()
	{
		$this->Institutions->query = [
			'sort_count' => ''
		];
		$query = $this->Institutions->getFilter();
		$this->assertArrayHasKey('sort_count', $query);
		$this->assertTrue($query['sort_count']);
		$this->assertArrayHasKey('course_count', $query);
		$this->assertTrue($query['course_count']);

		$this->Institutions->query = ['count_recent' => true];
		$this->Institutions->getFilter();
		$this->assertTrue($this->Institutions->query['count_recent']);
		$this->assertTrue($this->Institutions->query['course_count']);

		$this->Institutions->query = ['group' => ''];
		$query = $this->Institutions->getFilter();
		$this->assertArrayHasKey('group', $query);
		$this->assertTrue($query['group']);

		$this->Institutions->query = ['group' => '', 'country_id' => '2'];
		$query = $this->Institutions->getFilter();
		$this->assertArrayHasKey('group', $query);
		$this->assertArrayHasKey('country_id', $query);
		$this->assertTrue(ctype_digit($query['country_id']));

		$this->Institutions->query = ['group' => '', 'country_id' => '1,2'];
		$query = $this->Institutions->getFilter();
		$this->assertArrayHasKey('group', $query);
		$this->assertArrayNotHasKey('country_id', $query);

		$this->Institutions->query = ['group' => '', 'city_id' => '2'];
		$query = $this->Institutions->getFilter();
		$this->assertArrayHasKey('group', $query);
		$this->assertArrayHasKey('city_id', $query);
		$this->assertTrue(ctype_digit($query['city_id']));

		$this->Institutions->query = ['country_id' => '3', 'city_id' => '2'];
		$query = $this->Institutions->getFilter();
		$this->assertArrayNotHasKey('country_id', $query);
		$this->assertArrayHasKey('city_id', $query);
	}

	public function testGetInstitution()
	{
		$institution = $this->Institutions->getInstitution(1);
		$this->__testInstitution($institution);
	}

	private function __testInstitution($institution = [])
	{
		$this->assertArrayHasKey('course_count', $institution);
		$this->assertArrayHasKey('id', $institution);
		$this->assertArrayHasKey('name', $institution);
		$this->assertArrayHasKey('country_id', $institution);
		$this->assertArrayHasKey('country', $institution);
		$this->assertArrayHasKey('name', $institution['country']);
		$this->assertArrayHasKey('city', $institution);
		$this->assertArrayHasKey('city_id', $institution);
		$this->assertArrayHasKey('name', $institution['city']);
	}

	public function testGetInstitutions()
	{
		$this->Institutions->query = ['course_count' => true];
		$institutions = $this->Institutions->getInstitutions();
		foreach ($institutions as $institution) {
			$this->assertArrayHasKey('course_count', $institution);
		}
		$this->Institutions->query = [];
		$institutions = $this->Institutions->getInstitutions();
		foreach ($institutions as $institution) {
			// we retrieve an object here
			$this->assertObjectNotHasAttribute('course_count', $institution);
			$this->__testInstitution($institution);
		}
		$this->Institutions->query = ['course_count' => true, 'sort_count' => true];
		$institutions = $this->Institutions->getInstitutions();
		$last = null;
		foreach ($institutions as $institution) {
			if ($last !== null)
				$this->assertTrue($last > $institution['course_count']);
			$last = $institution['course_count'];
		}
		$this->Institutions->query = ['group' => true, 'country_id' => '1'];
		$institutions = $this->Institutions->getInstitutions();
		foreach ($institutions as $country => $c_institutions) {
			$this->assertTrue($country === 'Lorem ipsum dolor sit amet');
			$this->assertTrue(is_array($c_institutions));
			foreach ($c_institutions as $key => $institution) {
				$this->assertTrue(is_integer($key));
				$this->assertNotEmpty($institution['name']);
				$this->assertNotEmpty($institution['id']);
			}
		}
	}
}
