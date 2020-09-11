<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JenisIzinProsesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JenisIzinProsesTable Test Case
 */
class JenisIzinProsesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\JenisIzinProsesTable
     */
    public $JenisIzinProses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.jenis_izin_proses',
        'app.jenis_izins',
        'app.daftar_proses',
        'app.alur_proses',
        'app.jenis_proses',
        'app.proses_permohonan',
        'app.permohonan_izin',
        'app.pemohon',
        'app.perusahaan',
        'app.jenis_usaha',
        'app.bidang_usaha',
        'app.jenis_usaha_perusahaan',
        'app.bidang_usaha_perusahaan',
        'app.desa',
        'app.kecamatan',
        'app.kabupaten',
        'app.provinsi',
        'app.izin',
        'app.jenis_izin',
        'app.unit',
        'app.unit_datatabel',
        'app.datatabel',
        'app.data_kolom',
        'app.canvas_element',
        'app.canvas',
        'app.form',
        'app.alur_pengajuan',
        'app.jenis_pengajuan',
        'app.penanggung_jawab',
        'app.jabatan',
        'app.pegawai',
        'app.instansi',
        'app.peran',
        'app.pengguna',
        'app.jenis_izin_pengguna',
        'app.unit_pengguna',
        'app.menu',
        'app.peran_menu',
        'app.canvas_tab',
        'app.kelompok_data',
        'app.template_data',
        'app.kelompok_tabel',
        'app.kelompok_kolom',
        'app.kelompok_kondisi',
        'app.element_option',
        'app.element',
        'app.dokumen_pendukung',
        'app.izin_paralel',
        'app.unit_terkait',
        'app.persyaratan',
        'app.jenis_usaha_permohonan',
        'app.bidang_usaha_permohonan',
        'app.forms'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('JenisIzinProses') ? [] : ['className' => 'App\Model\Table\JenisIzinProsesTable'];
        $this->JenisIzinProses = TableRegistry::get('JenisIzinProses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->JenisIzinProses);

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
