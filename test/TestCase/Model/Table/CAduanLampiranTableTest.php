<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CAduanLampiranTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CAduanLampiranTable Test Case
 */
class CAduanLampiranTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CAduanLampiranTable
     */
    public $CAduanLampiran;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.c_aduan_lampiran',
        'app.c_aduans',
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
        $config = TableRegistry::exists('CAduanLampiran') ? [] : ['className' => CAduanLampiranTable::class];
        $this->CAduanLampiran = TableRegistry::get('CAduanLampiran', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CAduanLampiran);

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
