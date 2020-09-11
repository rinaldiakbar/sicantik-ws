<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RestServicesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RestServicesTable Test Case
 */
class RestServicesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RestServicesTable
     */
    public $RestServices;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.rest_services',
        'app.datatabel',
        'app.data_kolom',
        'app.canvas_element',
        'app.canvas',
        'app.form',
        'app.unit',
        'app.penomoran_detail',
        'app.penomoran',
        'app.jenis_pengajuan',
        'app.jenis_izin',
        'app.instansi',
        'app.peran',
        'app.pengguna',
        'app.notifikasi_pengguna',
        'app.pegawai',
        'app.jabatan',
        'app.notifikasi_detail',
        'app.notifikasi',
        'app.daftar_proses',
        'app.jenis_izin_proses',
        'app.template_data',
        'app.kelompok_data',
        'app.kelompok_tabel',
        'app.kelompok_kolom',
        'app.kelompok_kondisi',
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
        'app.jenis_proses',
        'app.jenis_proyek',
        'app.retribusi_detail',
        'app.report_component_details',
        'app.report_components',
        'app.alur_proses',
        'app.penanggung_jawab',
        'app.alur_pengajuan',
        'app.jenis_izin_pengguna',
        'app.jenis_proses_pengguna',
        'app.unit_pengguna',
        'app.menu',
        'app.peran_menu',
        'app.kalender',
        'app.gateway_user',
        'app.rest_user',
        'app.izin_paralel',
        'app.unit_terkait',
        'app.tarif_item',
        'app.tarif_harga',
        'app.formula_retribusi',
        'app.unit_datatabel',
        'app.service_eksternal',
        'app.canvas_tab',
        'app.element_option'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('RestServices') ? [] : ['className' => RestServicesTable::class];
        $this->RestServices = TableRegistry::get('RestServices', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RestServices);

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
