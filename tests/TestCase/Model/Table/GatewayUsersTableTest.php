<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GatewayUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GatewayUsersTable Test Case
 */
class GatewayUsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\GatewayUsersTable
     */
    public $GatewayUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.gateway_users',
        'app.messages'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('GatewayUsers') ? [] : ['className' => GatewayUsersTable::class];
        $this->GatewayUsers = TableRegistry::get('GatewayUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GatewayUsers);

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
