<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MenuModuleTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MenuModuleTable Test Case
 */
class MenuModuleTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MenuModuleTable
     */
    public $MenuModule;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.menu_module',
        'app.menus'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('MenuModule') ? [] : ['className' => MenuModuleTable::class];
        $this->MenuModule = TableRegistry::get('MenuModule', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MenuModule);

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
