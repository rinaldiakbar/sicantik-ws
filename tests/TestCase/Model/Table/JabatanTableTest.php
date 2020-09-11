<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JabatanTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JabatanTable Test Case
 */
class JabatanTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\JabatanTable
     */
    public $Jabatan;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.jabatan',
        'app.instansis',
        'app.notifikasi_detail',
        'app.notifikasi',
        'app.jenis_izin',
        'app.unit',
        'app.penomoran_detail',
        'app.penomoran',
        'app.instansi',
        'app.peran',
        'app.pengguna',
        'app.pegawai',
        'app.jenis_izin_pengguna',
        'app.jenis_proses',
        'app.daftar_proses',
        'app.jenis_izin_proses',
        'app.jenis_pengajuan',
        'app.alur_proses',
        'app.alur_pengajuan',
        'app.form',
        'app.proses_permohonan',
        'app.permohonan_izin',
        'app.pemohon',
        'app.desa',
        'app.kecamatan',
        'app.kabupaten',
        'app.provinsi',
        'app.izin',
        'app.perusahaan',
        'app.jenis_usaha',
        'app.bidang_usaha',
        'app.jenis_usaha_perusahaan',
        'app.bidang_usaha_perusahaan',
        'app.latest_proses_permohonan',
        'app.template_data',
        'app.kelompok_data',
        'app.kelompok_tabel',
        'app.kelompok_kolom',
        'app.kelompok_kondisi',
        'app.canvas',
        'app.datatabel',
        'app.data_kolom',
        'app.canvas_element',
        'app.element_option',
        'app.unit_datatabel',
        'app.canvas_tab',
        'app.jenis_proyek',
        'app.persyaratan',
        'app.retribusi_detail',
        'app.jenis_proses_pengguna',
        'app.unit_pengguna',
        'app.menu',
        'app.peran_menu',
        'app.kalender',
        'app.dokumen_pendukung',
        'app.izin_paralel',
        'app.unit_terkait',
        'app.tarif_item',
        'app.tarif_harga',
        'app.formula_retribusi',
        'app.penanggung_jawab'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Jabatan') ? [] : ['className' => 'App\Model\Table\JabatanTable'];
        $this->Jabatan = TableRegistry::get('Jabatan', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Jabatan);

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
