<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JenisUsahaTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JenisUsahaTable Test Case
 */
class JenisUsahaTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\JenisUsahaTable
     */
    public $JenisUsaha;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.jenis_usaha',
        'app.bidang_usahas',
        'app.jenis_usaha_permohonan',
        'app.perusahaan',
        'app.bidang_usaha',
        'app.bidang_usaha_permohonan',
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
        $config = TableRegistry::exists('JenisUsaha') ? [] : ['className' => 'App\Model\Table\JenisUsahaTable'];
        $this->JenisUsaha = TableRegistry::get('JenisUsaha', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->JenisUsaha);

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
