<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BidangUsahaTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BidangUsahaTable Test Case
 */
class BidangUsahaTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BidangUsahaTable
     */
    public $BidangUsaha;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.bidang_usaha',
        'app.bidang_usaha_permohonan',
        'app.jenis_usaha',
        'app.bidang_usahas',
        'app.jenis_usaha_permohonan',
        'app.perusahaan',
        'app.desa',
        'app.kecamatan',
        'app.kabupaten',
        'app.provinsi',
        'app.izin',
        'app.jenis_izin',
        'app.unit',
        'app.pegawai',
        'app.instansi',
        'app.pengguna',
        'app.peran',
        'app.menu',
        'app.peran_menu',
        'app.jenis_izin_pengguna',
        'app.unit_pengguna',
        'app.alur_pengajuan',
        'app.jenis_pengajuan',
        'app.alur_proses',
        'app.jenis_proses',
        'app.daftar_proses',
        'app.proses_permohonan',
        'app.permohonan_izin',
        'app.pemohon',
        'app.persyaratan',
        'app.forms',
        'app.penanggung_jawab',
        'app.jabatan',
        'app.dokumen_pendukung',
        'app.izin_paralel',
        'app.unit_terkait'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('BidangUsaha') ? [] : ['className' => 'App\Model\Table\BidangUsahaTable'];
        $this->BidangUsaha = TableRegistry::get('BidangUsaha', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BidangUsaha);

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
