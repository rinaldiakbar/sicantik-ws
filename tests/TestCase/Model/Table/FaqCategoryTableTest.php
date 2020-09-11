<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FaqCategoryTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FaqCategoryTable Test Case
 */
class FaqCategoryTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FaqCategoryTable
     */
    public $FaqCategory;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.faq_category',
        'app.instansi',
        'app.faq'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FaqCategory') ? [] : ['className' => FaqCategoryTable::class];
        $this->FaqCategory = TableRegistry::getTableLocator()->get('FaqCategory', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FaqCategory);

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
