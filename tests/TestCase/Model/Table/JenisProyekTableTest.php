<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JenisProyekTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JenisProyekTable Test Case
 */
class JenisProyekTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\JenisProyekTable
     */
    public $JenisProyek;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.jenis_proyek',
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
        'app.penomoran_detail',
        'app.penomoran',
        'app.instansi',
        'app.peran',
        'app.pengguna',
        'app.pegawai',
        'app.jenis_izin_pengguna',
        'app.unit_pengguna',
        'app.menu',
        'app.peran_menu',
        'app.unit_datatabel',
        'app.datatabel',
        'app.data_kolom',
        'app.canvas_element',
        'app.canvas',
        'app.form',
        'app.alur_pengajuan',
        'app.daftar_proses',
        'app.jenis_izin_proses',
        'app.jenis_pengajuan',
        'app.alur_proses',
        'app.template_data',
        'app.kelompok_data',
        'app.kelompok_tabel',
        'app.kelompok_kolom',
        'app.kelompok_kondisi',
        'app.proses_permohonan',
        'app.jenis_proses',
        'app.canvas_tab',
        'app.element_option',
        'app.dokumen_pendukung',
        'app.izin_paralel',
        'app.unit_terkait',
        'app.tarif_item',
        'app.tarif_harga',
        'app.formula_retribusi',
        'app.persyaratan',
        'app.retribusi_detail',
        'app.jenis_usaha_permohonan',
        'app.bidang_usaha_permohonan'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('JenisProyek') ? [] : ['className' => 'App\Model\Table\JenisProyekTable'];
        $this->JenisProyek = TableRegistry::get('JenisProyek', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->JenisProyek);

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
}
