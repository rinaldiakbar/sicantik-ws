<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\QueueJobsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\QueueJobsTable Test Case
 */
class QueueJobsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\QueueJobsTable
     */
    public $QueueJobs;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.queue_jobs'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('QueueJobs') ? [] : ['className' => 'App\Model\Table\QueueJobsTable'];
        $this->QueueJobs = TableRegistry::get('QueueJobs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->QueueJobs);

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
