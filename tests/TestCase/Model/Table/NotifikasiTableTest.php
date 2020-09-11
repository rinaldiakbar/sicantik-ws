<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NotifikasiTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\NotifikasiTable Test Case
 */
class NotifikasiTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\NotifikasiTable
     */
    public $Notifikasi;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.notifikasi',
        'app.jenis_izins',
        'app.notifikasi_detail'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Notifikasi') ? [] : ['className' => 'App\Model\Table\NotifikasiTable'];
        $this->Notifikasi = TableRegistry::get('Notifikasi', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Notifikasi);

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
