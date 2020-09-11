<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UnitTerkaitTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UnitTerkaitTable Test Case
 */
class UnitTerkaitTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UnitTerkaitTable
     */
    public $UnitTerkait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.unit_terkait',
        'app.jenis_izins',
        'app.unit'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UnitTerkait') ? [] : ['className' => 'App\Model\Table\UnitTerkaitTable'];
        $this->UnitTerkait = TableRegistry::get('UnitTerkait', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UnitTerkait);

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
