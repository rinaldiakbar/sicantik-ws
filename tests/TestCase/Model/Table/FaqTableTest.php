<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FaqTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FaqTable Test Case
 */
class FaqTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FaqTable
     */
    public $Faq;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.faq',
        'app.instansi',
        'app.faq_category'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Faq') ? [] : ['className' => FaqTable::class];
        $this->Faq = TableRegistry::getTableLocator()->get('Faq', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Faq);

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
