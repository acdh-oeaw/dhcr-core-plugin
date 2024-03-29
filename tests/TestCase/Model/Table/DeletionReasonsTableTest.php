<?php

namespace DhcrCore\Test\TestCase\Model\Table;

use DhcrCore\Model\Table\DeletionReasonsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DeletionReasonsTable Test Case
 */
class DeletionReasonsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DeletionReasonsTable
     */
    public $DeletionReasons;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.DhcrCore.DeletionReasons',
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
        $config = TableRegistry::getTableLocator()->exists('DeletionReasons') ? [] : ['className' => DeletionReasonsTable::class];
        $this->DeletionReasons = TableRegistry::getTableLocator()->get('DeletionReasons', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->DeletionReasons);

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
