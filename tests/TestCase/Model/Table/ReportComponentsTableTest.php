<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReportComponentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReportComponentsTable Test Case
 */
class ReportComponentsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ReportComponentsTable
     */
    public $ReportComponents;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.report_components',
        'app.jenis_izins',
        'app.instansis',
        'app.report_component_details'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ReportComponents') ? [] : ['className' => ReportComponentsTable::class];
        $this->ReportComponents = TableRegistry::get('ReportComponents', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ReportComponents);

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
