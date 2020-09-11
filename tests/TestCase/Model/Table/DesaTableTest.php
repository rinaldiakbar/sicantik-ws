<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DesaTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DesaTable Test Case
 */
class DesaTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DesaTable
     */
    public $Desa;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.desa',
        'app.kecamatans'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Desa') ? [] : ['className' => 'App\Model\Table\DesaTable'];
        $this->Desa = TableRegistry::get('Desa', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Desa);

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
