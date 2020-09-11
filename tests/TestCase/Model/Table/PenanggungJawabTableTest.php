<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PenanggungJawabTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PenanggungJawabTable Test Case
 */
class PenanggungJawabTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PenanggungJawabTable
     */
    public $PenanggungJawab;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.penanggung_jawab',
        'app.alur_pengajuans',
        'app.unit',
        'app.jabatans',
        'app.pegawais'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('PenanggungJawab') ? [] : ['className' => 'App\Model\Table\PenanggungJawabTable'];
        $this->PenanggungJawab = TableRegistry::get('PenanggungJawab', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PenanggungJawab);

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
