<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UnitTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UnitTable Test Case
 */
class UnitTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UnitTable
     */
    public $Unit;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.unit',
        'app.pegawai',
        'app.pengguna',
        'app.peran',
        'app.unit',
        'app.parent_unit',
        'app.unit_pengguna',
        'app.unit_pengguna',
        'app.peran_menu',
        'app.jenis_izin',
        'app.jenis_izin_pengguna'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Unit') ? [] : ['className' => 'App\Model\Table\UnitTable'];
        $this->Unit = TableRegistry::get('Unit', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Unit);

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
