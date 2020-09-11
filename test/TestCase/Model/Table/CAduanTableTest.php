<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CAduanTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CAduanTable Test Case
 */
class CAduanTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CAduanTable
     */
    public $CAduan;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.c_aduan',
        'app.instansis',
        'app.c_aduan_komentar',
        'app.c_aduan_lampiran'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CAduan') ? [] : ['className' => CAduanTable::class];
        $this->CAduan = TableRegistry::get('CAduan', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CAduan);

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
