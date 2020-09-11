<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NotifikasiPenggunaTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\NotifikasiPenggunaTable Test Case
 */
class NotifikasiPenggunaTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\NotifikasiPenggunaTable
     */
    public $NotifikasiPengguna;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.notifikasi_pengguna',
        'app.penggunas'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('NotifikasiPengguna') ? [] : ['className' => NotifikasiPenggunaTable::class];
        $this->NotifikasiPengguna = TableRegistry::get('NotifikasiPengguna', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->NotifikasiPengguna);

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
