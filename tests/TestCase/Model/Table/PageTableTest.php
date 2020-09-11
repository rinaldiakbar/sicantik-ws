<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PageTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PageTable Test Case
 */
class PageTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PageTable
     */
    public $Page;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.page',
        'app.instansis',
        'app.page_content',
        'app.page_tab'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Page') ? [] : ['className' => PageTable::class];
        $this->Page = TableRegistry::get('Page', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Page);

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
