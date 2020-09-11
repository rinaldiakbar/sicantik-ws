<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AlurProsesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AlurProsesTable Test Case
 */
class AlurProsesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AlurProsesTable
     */
    public $AlurProses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.alur_proses'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AlurProses') ? [] : ['className' => 'App\Model\Table\AlurProsesTable'];
        $this->AlurProses = TableRegistry::get('AlurProses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AlurProses);

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
}
