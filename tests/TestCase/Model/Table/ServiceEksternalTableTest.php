<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ServiceEksternalTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ServiceEksternalTable Test Case
 */
class ServiceEksternalTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ServiceEksternalTable
     */
    public $ServiceEksternal;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.service_eksternal'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ServiceEksternal') ? [] : ['className' => 'App\Model\Table\ServiceEksternalTable'];
        $this->ServiceEksternal = TableRegistry::get('ServiceEksternal', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ServiceEksternal);

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
