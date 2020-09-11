<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\KalenderTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\KalenderTable Test Case
 */
class KalenderTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\KalenderTable
     */
    public $Kalender;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.kalender',
        'app.instansis'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Kalender') ? [] : ['className' => 'App\Model\Table\KalenderTable'];
        $this->Kalender = TableRegistry::get('Kalender', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Kalender);

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
}
