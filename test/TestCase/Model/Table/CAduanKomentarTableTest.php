<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CAduanKomentarTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CAduanKomentarTable Test Case
 */
class CAduanKomentarTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CAduanKomentarTable
     */
    public $CAduanKomentar;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.c_aduan_komentar',
        'app.c_aduans',
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
        $config = TableRegistry::exists('CAduanKomentar') ? [] : ['className' => CAduanKomentarTable::class];
        $this->CAduanKomentar = TableRegistry::get('CAduanKomentar', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CAduanKomentar);

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
