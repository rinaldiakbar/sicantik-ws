<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CanvasTabTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CanvasTabTable Test Case
 */
class CanvasTabTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CanvasTabTable
     */
    public $CanvasTab;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.canvas_tab',
        'app.canvas',
        'app.form',
        'app.unit',
        'app.pengguna',
        'app.peran',
        'app.instansi',
        'app.pegawai',
        'app.menu',
        'app.peran_menu',
        'app.jenis_izin',
        'app.alur_pengajuan',
        'app.jenis_pengajuan',
        'app.alur_proses',
        'app.daftar_proses',
        'app.jenis_proses',
        'app.proses_permohonan',
        'app.permohonan_izin',
        'app.pemohon',
        'app.perusahaan',
        'app.desa',
        'app.kecamatan',
        'app.kabupaten',
        'app.provinsi',
        'app.izin',
        'app.persyaratan',
        'app.jenis_usaha',
        'app.bidang_usaha',
        'app.jenis_usaha_permohonan',
        'app.bidang_usaha_permohonan',
        'app.penanggung_jawab',
        'app.jabatan',
        'app.dokumen_pendukung',
        'app.izin_paralel',
        'app.unit_terkait',
        'app.jenis_izin_pengguna',
        'app.unit_pengguna',
        'app.datatabel',
        'app.data_kolom',
        'app.canvas_element',
        'app.element_option',
        'app.element'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CanvasTab') ? [] : ['className' => 'App\Model\Table\CanvasTabTable'];
        $this->CanvasTab = TableRegistry::get('CanvasTab', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CanvasTab);

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
