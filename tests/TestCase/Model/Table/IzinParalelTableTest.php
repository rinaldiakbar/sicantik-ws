<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\IzinParalelTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\IzinParalelTable Test Case
 */
class IzinParalelTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\IzinParalelTable
     */
    public $IzinParalel;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.izin_paralel',
        'app.jenis_izins',
        'app.izin_paralels'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('IzinParalel') ? [] : ['className' => 'App\Model\Table\IzinParalelTable'];
        $this->IzinParalel = TableRegistry::get('IzinParalel', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->IzinParalel);

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
