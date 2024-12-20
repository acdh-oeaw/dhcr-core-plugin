<?php

namespace DhcrCore\Test\TestCase\Model\Table;

use DhcrCore\Model\Table\CountriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class CountriesTableTest extends TestCase
{
    public $Countries;
    public $fixtures = [
        'plugin.DhcrCore.Countries',
        'plugin.DhcrCore.Cities',
        'plugin.DhcrCore.Courses',
        'plugin.DhcrCore.Institutions',
        'plugin.DhcrCore.Users'
    ];

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Countries') ? [] : ['className' => CountriesTable::class];
        $this->Countries = TableRegistry::getTableLocator()->get('Countries', $config);
    }

    public function tearDown(): void
    {
        unset($this->Countries);
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
        $query = $this->Countries->getCleanQuery($query);
        $this->assertArrayNotHasKey('foo', $query);
        $this->assertArrayHasKey('sort_count', $query);
    }

    public function testGetFilter()
    {
        $this->Countries->query = [
            'sort_count' => ''
        ];
        $query = $this->Countries->getFilter();
        $this->assertArrayHasKey('sort_count', $query);
        $this->assertTrue($query['sort_count']);
        $this->assertArrayHasKey('course_count', $query);
        $this->assertTrue($query['course_count']);

        $this->Countries->query = ['count_recent' => true];
        $this->Countries->getFilter();
        $this->assertTrue($this->Countries->query['count_recent']);
        $this->assertTrue($this->Countries->query['course_count']);
    }

    public function testGetCountry()
    {
        $country = $this->Countries->getCountry(1);
        $this->assertArrayHasKey('course_count', $country);
    }

    public function testGetCountries()
    {
        $this->Countries->query = ['course_count' => true];
        $countries = $this->Countries->getCountries();
        foreach ($countries as $country) {
            $this->assertNotNull($country['course_count']);
        }
        $this->Countries->query = [];
        $countries = $this->Countries->getCountries();
        foreach ($countries as $country) {
            // we're dealing with an object here
            $this->assertObjectNotHasAttribute('course_count', $country);
        }
        $this->Countries->query = ['course_count' => true, 'sort_count' => true];
        $countries = $this->Countries->getCountries();
        $last = null;
        foreach ($countries as $country) {
            if ($last !== null)
                $this->assertTrue($last > $country['course_count']);
            $last = $country['course_count'];
        }

        // TODO: add an expired course for the test to succeed
        /*$countries = $this->Countries->getCountries();
        $this->Countries->query = ['course_count' => true,'count_recent' => false];
        $_countries = $this->Countries->getCountries();
        $this->assertNotEquals($countries, $_countries);*/
    }
}
