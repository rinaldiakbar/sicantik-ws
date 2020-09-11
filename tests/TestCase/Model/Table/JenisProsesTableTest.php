<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JenisProsesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JenisProsesTable Test Case
 */
class JenisProsesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\JenisProsesTable
     */
    public $JenisProses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.jenis_proses'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('JenisProses') ? [] : ['className' => 'App\Model\Table\JenisProsesTable'];
        $this->JenisProses = TableRegistry::get('JenisProses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->JenisProses);

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
