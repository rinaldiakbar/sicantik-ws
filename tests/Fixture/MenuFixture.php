<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MenuFixture
 *
 */
class MenuFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'menu';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'label_menu' => ['type' => 'string', 'length' => 100, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'fixed' => null],
        'tautan' => ['type' => 'string', 'length' => 500, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'fixed' => null],
        'parent_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
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
            'label_menu' => 'Lorem ipsum dolor sit amet',
            'tautan' => 'Lorem ipsum dolor sit amet',
            'parent_id' => 1
        ],
    ];
}
