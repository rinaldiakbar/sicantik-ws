<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JenisIzinTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JenisIzinTable Test Case
 */
class JenisIzinTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\JenisIzinTable
     */
    public $JenisIzin;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.jenis_izin',
        'app.pengguna',
        'app.peran',
        'app.unit',
        'app.pegawai',
        'app.unit',
        'app.instansi',
        'app.unit_pengguna',
        'app.menu',
        'app.peran_menu',
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
        $config = TableRegistry::exists('JenisIzin') ? [] : ['className' => 'App\Model\Table\JenisIzinTable'];
        $this->JenisIzin = TableRegistry::get('JenisIzin', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->JenisIzin);

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
