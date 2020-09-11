<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PenomoranDetailTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PenomoranDetailTable Test Case
 */
class PenomoranDetailTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PenomoranDetailTable
     */
    public $PenomoranDetail;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.penomoran_detail',
        'app.penomorans',
        'app.units'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('PenomoranDetail') ? [] : ['className' => 'App\Model\Table\PenomoranDetailTable'];
        $this->PenomoranDetail = TableRegistry::get('PenomoranDetail', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PenomoranDetail);

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
