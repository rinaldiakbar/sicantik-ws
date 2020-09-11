<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PegawaiTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PegawaiTable Test Case
 */
class PegawaiTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PegawaiTable
     */
    public $Pegawai;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.pegawai',
        'app.pengguna',
        'app.perans',
        'app.pegawais',
        'app.jenis_izin',
        'app.jenis_izin_pengguna',
        'app.unit',
        'app.peran',
        'app.unit',
        'app.menu',
        'app.peran_menu',
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
        $config = TableRegistry::exists('Pegawai') ? [] : ['className' => 'App\Model\Table\PegawaiTable'];
        $this->Pegawai = TableRegistry::get('Pegawai', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Pegawai);

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
