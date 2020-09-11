<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ElementOptionTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ElementOptionTable Test Case
 */
class ElementOptionTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ElementOptionTable
     */
    public $ElementOption;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.element_option',
        'app.element'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ElementOption') ? [] : ['className' => 'App\Model\Table\ElementOptionTable'];
        $this->ElementOption = TableRegistry::get('ElementOption', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ElementOption);

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
