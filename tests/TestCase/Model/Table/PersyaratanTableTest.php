<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PersyaratanTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PersyaratanTable Test Case
 */
class PersyaratanTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PersyaratanTable
     */
    public $Persyaratan;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.persyaratan',
        'app.permohonan',
        'app.persyaratan'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Persyaratan') ? [] : ['className' => 'App\Model\Table\PersyaratanTable'];
        $this->Persyaratan = TableRegistry::get('Persyaratan', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Persyaratan);

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
