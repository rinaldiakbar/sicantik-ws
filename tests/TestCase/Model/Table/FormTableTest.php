<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FormTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FormTable Test Case
 */
class FormTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FormTable
     */
    public $Form;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.form',
        'app.units',
        'app.alur_pengajuan',
        'app.jenis_izin',
        'app.unit',
        'app.pengguna',
        'app.peran',
        'app.instansi',
        'app.pegawai',
        'app.menu',
        'app.peran_menu',
        'app.jenis_izin_pengguna',
        'app.unit_pengguna',
        'app.dokumen_pendukung',
        'app.izin_paralel',
        'app.jenis_pengajuan',
        'app.alur_proses',
        'app.jenis_proses',
        'app.daftar_proses',
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
        'app.unit_terkait',
        'app.forms',
        'app.penanggung_jawab',
        'app.jabatan',
        'app.canvas'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Form') ? [] : ['className' => 'App\Model\Table\FormTable'];
        $this->Form = TableRegistry::get('Form', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Form);

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
