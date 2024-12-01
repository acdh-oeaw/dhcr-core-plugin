<?php

namespace DhcrCore\Test\TestCase\Model\Table;

use DhcrCore\Model\Table\DeletionReasonsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class DeletionReasonsTableTest extends TestCase
{
    public $DeletionReasons;
    public $fixtures = [
        'plugin.DhcrCore.DeletionReasons',
        'plugin.DhcrCore.Courses'
    ];

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('DeletionReasons') ? [] : ['className' => DeletionReasonsTable::class];
        $this->DeletionReasons = TableRegistry::getTableLocator()->get('DeletionReasons', $config);
    }

    public function tearDown(): void
    {
        unset($this->DeletionReasons);
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

    public function testGetDeletionReason()
    {
        $record = $this->DeletionReasons->getDeletionReason(1);
        $this->assertArrayHasKey('id', $record);
        $this->assertArrayHasKey('name', $record);
    }

    public function testGetDeletionReasons()
    {
        $records = $this->DeletionReasons->getDeletionReasons();
        foreach ($records as $record) {
            $this->assertArrayHasKey('id', $record);
            $this->assertArrayHasKey('name', $record);
        }
    }
}
