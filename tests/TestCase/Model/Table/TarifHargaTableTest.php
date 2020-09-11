<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TarifHargaTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TarifHargaTable Test Case
 */
class TarifHargaTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TarifHargaTable
     */
    public $TarifHarga;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.tarif_harga',
        'app.tarif_items'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TarifHarga') ? [] : ['className' => 'App\Model\Table\TarifHargaTable'];
        $this->TarifHarga = TableRegistry::get('TarifHarga', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TarifHarga);

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
