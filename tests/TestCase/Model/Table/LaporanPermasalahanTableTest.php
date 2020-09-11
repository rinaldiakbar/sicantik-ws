<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LaporanPermasalahanTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LaporanPermasalahanTable Test Case
 */
class LaporanPermasalahanTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LaporanPermasalahanTable
     */
    public $LaporanPermasalahan;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.laporan_permasalahan',
        'app.instansis'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('LaporanPermasalahan') ? [] : ['className' => LaporanPermasalahanTable::class];
        $this->LaporanPermasalahan = TableRegistry::get('LaporanPermasalahan', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LaporanPermasalahan);

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
