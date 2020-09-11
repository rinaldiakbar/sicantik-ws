<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\IzinTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\IzinTable Test Case
 */
class IzinTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\IzinTable
     */
    public $Izin;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.izin',
        'app.jenis_izins',
        'app.unit',
        'app.pemohon',
        'app.perusahaan',
        'app.permohonan_izin',
        'app.izins',
        'app.proses',
        'app.proses_permohonan',
        'app.permohonan'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Izin') ? [] : ['className' => 'App\Model\Table\IzinTable'];
        $this->Izin = TableRegistry::get('Izin', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Izin);

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
