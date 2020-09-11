<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\KecamatanTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\KecamatanTable Test Case
 */
class KecamatanTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\KecamatanTable
     */
    public $Kecamatan;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.kecamatan',
        'app.kabupatens',
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
        $config = TableRegistry::exists('Kecamatan') ? [] : ['className' => 'App\Model\Table\KecamatanTable'];
        $this->Kecamatan = TableRegistry::get('Kecamatan', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Kecamatan);

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
