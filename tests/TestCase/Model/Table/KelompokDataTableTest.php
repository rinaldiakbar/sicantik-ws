<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\KelompokDataTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\KelompokDataTable Test Case
 */
class KelompokDataTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\KelompokDataTable
     */
    public $KelompokData;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.kelompok_data',
        'app.template_data',
        'app.instansis'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('KelompokData') ? [] : ['className' => 'App\Model\Table\KelompokDataTable'];
        $this->KelompokData = TableRegistry::get('KelompokData', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->KelompokData);

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
