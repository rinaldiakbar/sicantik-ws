<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PemohonTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PemohonTable Test Case
 */
class PemohonTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PemohonTable
     */
    public $Pemohon;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.pemohon',
        'app.perusahaan',
        'app.desas',
        'app.kecamatans',
        'app.kabupatens',
        'app.provinsis',
        'app.izin',
        'app.permohonan_izin',
        'app.pemohon',
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
        $config = TableRegistry::exists('Pemohon') ? [] : ['className' => 'App\Model\Table\PemohonTable'];
        $this->Pemohon = TableRegistry::get('Pemohon', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Pemohon);

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
