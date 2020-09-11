<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DatatabelTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DatatabelTable Test Case
 */
class DatatabelTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DatatabelTable
     */
    public $Datatabel;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.datatabel',
        'app.data_kolom',
        'app.datatabels'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Datatabel') ? [] : ['className' => 'App\Model\Table\DatatabelTable'];
        $this->Datatabel = TableRegistry::get('Datatabel', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Datatabel);

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
