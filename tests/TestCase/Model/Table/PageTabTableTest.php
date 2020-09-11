<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PageTabTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PageTabTable Test Case
 */
class PageTabTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PageTabTable
     */
    public $PageTab;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.page_tab',
        'app.instansis',
        'app.pages'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('PageTab') ? [] : ['className' => PageTabTable::class];
        $this->PageTab = TableRegistry::get('PageTab', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PageTab);

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
