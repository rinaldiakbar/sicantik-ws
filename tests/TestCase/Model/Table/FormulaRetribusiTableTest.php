<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FormulaRetribusiTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FormulaRetribusiTable Test Case
 */
class FormulaRetribusiTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FormulaRetribusiTable
     */
    public $FormulaRetribusi;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.formula_retribusi',
        'app.jenis_izins'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('FormulaRetribusi') ? [] : ['className' => 'App\Model\Table\FormulaRetribusiTable'];
        $this->FormulaRetribusi = TableRegistry::get('FormulaRetribusi', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FormulaRetribusi);

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
