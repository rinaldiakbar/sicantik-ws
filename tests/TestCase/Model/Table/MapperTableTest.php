<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MapperTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MapperTable Test Case
 */
class MapperTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MapperTable
     */
    public $Mapper;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.mapper',
        'app.keys',
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
        $config = TableRegistry::exists('Mapper') ? [] : ['className' => 'App\Model\Table\MapperTable'];
        $this->Mapper = TableRegistry::get('Mapper', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Mapper);

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
