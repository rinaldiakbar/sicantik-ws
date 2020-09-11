<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PermohonanIzinTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PermohonanIzinTable Test Case
 */
class PermohonanIzinTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PermohonanIzinTable
     */
    public $PermohonanIzin;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.permohonan_izin',
        'app.pemohon',
        'app.perusahaan',
        'app.izins',
        'app.proses'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('PermohonanIzin') ? [] : ['className' => 'App\Model\Table\PermohonanIzinTable'];
        $this->PermohonanIzin = TableRegistry::get('PermohonanIzin', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PermohonanIzin);

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
