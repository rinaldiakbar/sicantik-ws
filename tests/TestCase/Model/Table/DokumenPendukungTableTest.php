<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DokumenPendukungTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DokumenPendukungTable Test Case
 */
class DokumenPendukungTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DokumenPendukungTable
     */
    public $DokumenPendukung;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.dokumen_pendukung',
        'app.jenis_izins'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('DokumenPendukung') ? [] : ['className' => 'App\Model\Table\DokumenPendukungTable'];
        $this->DokumenPendukung = TableRegistry::get('DokumenPendukung', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DokumenPendukung);

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
