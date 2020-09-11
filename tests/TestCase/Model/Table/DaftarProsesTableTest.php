<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DaftarProsesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DaftarProsesTable Test Case
 */
class DaftarProsesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DaftarProsesTable
     */
    public $DaftarProses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.daftar_proses',
        'app.alur_proses',
        'app.jenis_proses'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('DaftarProses') ? [] : ['className' => 'App\Model\Table\DaftarProsesTable'];
        $this->DaftarProses = TableRegistry::get('DaftarProses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DaftarProses);

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
