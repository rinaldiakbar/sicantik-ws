<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReportComponentDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReportComponentDetailsTable Test Case
 */
class ReportComponentDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ReportComponentDetailsTable
     */
    public $ReportComponentDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.report_component_details',
        'app.report_components',
        'app.jenis_izins',
        'app.instansis',
        'app.daftar_proses',
        'app.jenis_izin_proses',
        'app.jenis_pengajuan',
        'app.jenis_izin',
        'app.unit',
        'app.penomoran_detail',
        'app.penomoran',
        'app.instansi',
        'app.peran',
        'app.pengguna',
        'app.notifikasi_pengguna',
        'app.pegawai',
        'app.jenis_izin_pengguna',
        'app.jenis_proses',
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
        'app.dokumen_pemohon',
        'app.jenis_dokumen',
        'app.dokumen_pendukung',
        'app.persyaratan',
        'app.latest_proses_permohonan',
        'app.form',
        'app.service_eksternal',
        'app.alur_pengajuan',
        'app.canvas',
        'app.datatabel',
        'app.data_kolom',
        'app.canvas_element',
        'app.kelompok_data',
        'app.template_data',
        'app.kelompok_tabel',
        'app.kelompok_kolom',
        'app.kelompok_kondisi',
        'app.element_option',
        'app.unit_datatabel',
        'app.canvas_tab',
        'app.jenis_proyek',
        'app.retribusi_detail',
        'app.jenis_proses_pengguna',
        'app.unit_pengguna',
        'app.menu',
        'app.peran_menu',
        'app.kalender',
        'app.gateway_user',
        'app.izin_paralel',
        'app.unit_terkait',
        'app.tarif_item',
        'app.tarif_harga',
        'app.notifikasi',
        'app.notifikasi_detail',
        'app.jabatan',
        'app.penanggung_jawab',
        'app.formula_retribusi',
        'app.alur_proses',
        'app.pegawais'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ReportComponentDetails') ? [] : ['className' => ReportComponentDetailsTable::class];
        $this->ReportComponentDetails = TableRegistry::get('ReportComponentDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ReportComponentDetails);

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
