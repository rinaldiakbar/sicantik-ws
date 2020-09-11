<?php
namespace App\Test\TestCase\Controller;

use App\Controller\JenisPengajuanController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\JenisPengajuanController Test Case
 */
class JenisPengajuanControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.jenis_pengajuan',
        'app.jenis_izins',
        'app.alur_proses',
        'app.jenis_proses',
        'app.daftar_proses',
        'app.alur_pengajuan',
        'app.jenis_pengajuans',
        'app.forms',
        'app.penanggung_jawab',
        'app.alur_pengajuans',
        'app.unit',
        'app.jabatans',
        'app.pegawais'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
