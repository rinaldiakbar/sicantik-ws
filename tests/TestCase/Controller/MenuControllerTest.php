<?php
namespace App\Test\TestCase\Controller;

use App\Controller\MenuController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\MenuController Test Case
 */
class MenuControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.menu',
        'app.peran',
        'app.unit',
        'app.pegawai',
        'app.pengguna',
        'app.jenis_izin',
        'app.jenis_izin_pengguna',
        'app.unit_pengguna',
        'app.peran_menu'
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
