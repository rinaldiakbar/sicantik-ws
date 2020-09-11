<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * KalenderFixture
 *
 */
class KalenderFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'kalender';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'instansi_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'tipe' => ['type' => 'string', 'length' => 2, 'default' => 'H', 'null' => false, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'nama_hari' => ['type' => 'string', 'length' => 12, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'idx_hari' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'tanggal' => ['type' => 'date', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'dibuat_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'tgl_dibuat' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'tgl_diubah' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'diubah_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'kalender_instansi_id' => ['type' => 'index', 'columns' => ['instansi_id'], 'length' => []],
            'kalender_tipe' => ['type' => 'index', 'columns' => ['tipe'], 'length' => []],
            'kalender_nama_hari' => ['type' => 'index', 'columns' => ['nama_hari'], 'length' => []],
            'kalender_idx_hari' => ['type' => 'index', 'columns' => ['idx_hari'], 'length' => []],
            'kalender_tanggal' => ['type' => 'index', 'columns' => ['tanggal'], 'length' => []],
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
            'instansi_id' => 1,
            'tipe' => '',
            'nama_hari' => 'Lorem ipsu',
            'idx_hari' => 1,
            'tanggal' => '2016-12-17',
            'dibuat_oleh' => 'Lorem ipsum dolor sit a',
            'tgl_dibuat' => 1481956335,
            'tgl_diubah' => 1481956335,
            'diubah_oleh' => 'Lorem ipsum dolor sit a'
        ],
    ];
}
