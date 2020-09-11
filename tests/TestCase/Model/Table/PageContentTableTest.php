<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PageContentTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PageContentTable Test Case
 */
class PageContentTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PageContentTable
     */
    public $PageContent;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.page_content',
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
        $config = TableRegistry::exists('PageContent') ? [] : ['className' => PageContentTable::class];
        $this->PageContent = TableRegistry::get('PageContent', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PageContent);

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
