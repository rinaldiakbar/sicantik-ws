<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TemplateDataTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TemplateDataTable Test Case
 */
class TemplateDataTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TemplateDataTable
     */
    public $TemplateData;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.template_data',
        'app.instansis',
        'app.kelompok_data'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TemplateData') ? [] : ['className' => 'App\Model\Table\TemplateDataTable'];
        $this->TemplateData = TableRegistry::get('TemplateData', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TemplateData);

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
