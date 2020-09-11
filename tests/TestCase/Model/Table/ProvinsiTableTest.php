<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProvinsiTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProvinsiTable Test Case
 */
class ProvinsiTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProvinsiTable
     */
    public $Provinsi;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.provinsi',
        'app.kabupaten',
        'app.kecamatan',
        'app.desa'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Provinsi') ? [] : ['className' => 'App\Model\Table\ProvinsiTable'];
        $this->Provinsi = TableRegistry::get('Provinsi', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Provinsi);

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
