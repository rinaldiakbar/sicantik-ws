<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DataKolomTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DataKolomTable Test Case
 */
class DataKolomTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DataKolomTable
     */
    public $DataKolom;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.data_kolom',
        'app.datatabels'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('DataKolom') ? [] : ['className' => 'App\Model\Table\DataKolomTable'];
        $this->DataKolom = TableRegistry::get('DataKolom', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DataKolom);

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
