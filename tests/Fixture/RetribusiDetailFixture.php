<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RetribusiDetailFixture
 *
 */
class RetribusiDetailFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'retribusi_detail';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'kode_item' => ['type' => 'string', 'length' => 100, 'default' => null, 'null' => false, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'nama_item' => ['type' => 'string', 'length' => 100, 'default' => null, 'null' => false, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'satuan' => ['type' => 'string', 'length' => 20, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'permohonan_izin_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'harga' => ['type' => 'float', 'length' => null, 'default' => '0', 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'jumlah' => ['type' => 'integer', 'length' => 10, 'default' => '0', 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'subtotal' => ['type' => 'float', 'length' => null, 'default' => '0', 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        '_indexes' => [
            'retribusi_detail_kode_item' => ['type' => 'index', 'columns' => ['kode_item'], 'length' => []],
            'retribusi_detail_nama_item' => ['type' => 'index', 'columns' => ['nama_item'], 'length' => []],
            'retribusi_detail_satuan' => ['type' => 'index', 'columns' => ['satuan'], 'length' => []],
            'retribusi_detail_permohonan_izin_id' => ['type' => 'index', 'columns' => ['permohonan_izin_id'], 'length' => []],
        ],
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
            'kode_item' => 'Lorem ipsum dolor sit amet',
            'nama_item' => 'Lorem ipsum dolor sit amet',
            'satuan' => 'Lorem ipsum dolor ',
            'permohonan_izin_id' => 1,
            'harga' => 1,
            'jumlah' => 1,
            'subtotal' => 1
        ],
    ];
}
