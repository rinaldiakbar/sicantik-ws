<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JenisPengajuanTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JenisPengajuanTable Test Case
 */
class JenisPengajuanTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\JenisPengajuanTable
     */
    public $JenisPengajuan;

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
        'app.alur_pengajuan'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('JenisPengajuan') ? [] : ['className' => 'App\Model\Table\JenisPengajuanTable'];
        $this->JenisPengajuan = TableRegistry::get('JenisPengajuan', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->JenisPengajuan);

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
