<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\KabupatenTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\KabupatenTable Test Case
 */
class KabupatenTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\KabupatenTable
     */
    public $Kabupaten;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.kabupaten',
        'app.provinsis',
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
        $config = TableRegistry::exists('Kabupaten') ? [] : ['className' => 'App\Model\Table\KabupatenTable'];
        $this->Kabupaten = TableRegistry::get('Kabupaten', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Kabupaten);

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
