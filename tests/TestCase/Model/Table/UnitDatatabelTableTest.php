<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UnitDatatabelTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UnitDatatabelTable Test Case
 */
class UnitDatatabelTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UnitDatatabelTable
     */
    public $UnitDatatabel;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.unit_datatabel',
        'app.datatabels',
        'app.units'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UnitDatatabel') ? [] : ['className' => 'App\Model\Table\UnitDatatabelTable'];
        $this->UnitDatatabel = TableRegistry::get('UnitDatatabel', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UnitDatatabel);

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
