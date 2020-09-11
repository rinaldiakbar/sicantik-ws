<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DataKolomFixture
 *
 */
class DataKolomFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'data_kolom';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'datatabel_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_kolom' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'fixed' => null],
        'label' => ['type' => 'string', 'length' => 100, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'fixed' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'datatabel_id' => 1,
            'data_kolom' => 'Lorem ipsum dolor sit a',
            'label' => 'Lorem ipsum dolor sit amet'
        ],
    ];
}
