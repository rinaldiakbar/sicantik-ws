<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PenggunaTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PenggunaTable Test Case
 */
class PenggunaTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PenggunaTable
     */
    public $Pengguna;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.pengguna',
        'app.peran',
        'app.unit',
        'app.menu',
        'app.peran_menu',
        'app.pegawai',
        'app.jenis_izin',
        'app.jenis_izin_pengguna',
        'app.unit',
        'app.unit_pengguna'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Pengguna') ? [] : ['className' => 'App\Model\Table\PenggunaTable'];
        $this->Pengguna = TableRegistry::get('Pengguna', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Pengguna);

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
