<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CanvasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CanvasTable Test Case
 */
class CanvasTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CanvasTable
     */
    public $Canvas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.canvas',
        'app.forms',
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
        $config = TableRegistry::exists('Canvas') ? [] : ['className' => 'App\Model\Table\CanvasTable'];
        $this->Canvas = TableRegistry::get('Canvas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Canvas);

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
