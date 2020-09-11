<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PeranTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PeranTable Test Case
 */
class PeranTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PeranTable
     */
    public $Peran;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.peran',
        'app.unit',
        'app.pegawai',
        'app.pengguna',
        'app.jenis_izin',
        'app.jenis_izin_pengguna',
        'app.unit_pengguna',
        'app.peran_menu'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Peran') ? [] : ['className' => 'App\Model\Table\PeranTable'];
        $this->Peran = TableRegistry::get('Peran', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Peran);

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
