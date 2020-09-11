<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RetribusiDetailTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RetribusiDetailTable Test Case
 */
class RetribusiDetailTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RetribusiDetailTable
     */
    public $RetribusiDetail;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.retribusi_detail',
        'app.permohonan_izins'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('RetribusiDetail') ? [] : ['className' => 'App\Model\Table\RetribusiDetailTable'];
        $this->RetribusiDetail = TableRegistry::get('RetribusiDetail', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RetribusiDetail);

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
