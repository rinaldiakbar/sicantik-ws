<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JenisDokumenTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JenisDokumenTable Test Case
 */
class JenisDokumenTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\JenisDokumenTable
     */
    public $JenisDokumen;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.jenis_dokumen'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('JenisDokumen') ? [] : ['className' => 'App\Model\Table\JenisDokumenTable'];
        $this->JenisDokumen = TableRegistry::get('JenisDokumen', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->JenisDokumen);

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
}
