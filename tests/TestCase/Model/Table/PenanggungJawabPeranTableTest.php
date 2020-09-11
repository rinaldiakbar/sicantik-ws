<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PenanggungJawabPeranTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PenanggungJawabPeranTable Test Case
 */
class PenanggungJawabPeranTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PenanggungJawabPeranTable
     */
    public $PenanggungJawabPeran;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.penanggung_jawab_peran',
        'app.perans',
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
        $config = TableRegistry::exists('PenanggungJawabPeran') ? [] : ['className' => PenanggungJawabPeranTable::class];
        $this->PenanggungJawabPeran = TableRegistry::get('PenanggungJawabPeran', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PenanggungJawabPeran);

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
