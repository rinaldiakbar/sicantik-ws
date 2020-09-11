<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TarifItemTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TarifItemTable Test Case
 */
class TarifItemTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TarifItemTable
     */
    public $TarifItem;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.tarif_item',
        'app.jenis_izins',
        'app.tarif_harga'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TarifItem') ? [] : ['className' => 'App\Model\Table\TarifItemTable'];
        $this->TarifItem = TableRegistry::get('TarifItem', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TarifItem);

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
