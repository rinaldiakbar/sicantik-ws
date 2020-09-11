<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PerusahaanTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PerusahaanTable Test Case
 */
class PerusahaanTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PerusahaanTable
     */
    public $Perusahaan;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.perusahaan',
        'app.jenis_usahas',
        'app.bidang_usahas',
        'app.desas',
        'app.kecamatans',
        'app.kabupatens',
        'app.provinsis',
        'app.izin',
        'app.pemohon',
        'app.perusahaan',
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
        $config = TableRegistry::exists('Perusahaan') ? [] : ['className' => 'App\Model\Table\PerusahaanTable'];
        $this->Perusahaan = TableRegistry::get('Perusahaan', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Perusahaan);

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
