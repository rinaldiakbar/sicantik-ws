<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DokumenPemohonTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DokumenPemohonTable Test Case
 */
class DokumenPemohonTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DokumenPemohonTable
     */
    public $DokumenPemohon;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.dokumen_pemohon',
        'app.jenis_dokumens'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('DokumenPemohon') ? [] : ['className' => 'App\Model\Table\DokumenPemohonTable'];
        $this->DokumenPemohon = TableRegistry::get('DokumenPemohon', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DokumenPemohon);

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
