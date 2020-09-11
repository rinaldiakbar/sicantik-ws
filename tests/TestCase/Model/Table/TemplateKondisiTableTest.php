<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TemplateKondisiTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TemplateKondisiTable Test Case
 */
class TemplateKondisiTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TemplateKondisiTable
     */
    public $TemplateKondisi;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.template_kondisi',
        'app.kelompok_data',
        'app.template_data',
        'app.instansi',
        'app.pegawai',
        'app.unit',
        'app.peran',
        'app.pengguna',
        'app.jenis_izin',
        'app.alur_pengajuan',
        'app.jenis_pengajuan',
        'app.alur_proses',
        'app.jenis_proses',
        'app.daftar_proses',
        'app.forms',
        'app.penanggung_jawab',
        'app.jabatan',
        'app.dokumen_pendukung',
        'app.izin_paralel',
        'app.unit_terkait',
        'app.permohonan_izin',
        'app.pemohon',
        'app.perusahaan',
        'app.jenis_usaha',
        'app.bidang_usaha',
        'app.desa',
        'app.kecamatan',
        'app.kabupaten',
        'app.provinsi',
        'app.izin',
        'app.proses_permohonan',
        'app.proses',
        'app.persyaratan',
        'app.jenis_izin_pengguna',
        'app.unit_pengguna',
        'app.menu',
        'app.peran_menu'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TemplateKondisi') ? [] : ['className' => 'App\Model\Table\TemplateKondisiTable'];
        $this->TemplateKondisi = TableRegistry::get('TemplateKondisi', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TemplateKondisi);

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
