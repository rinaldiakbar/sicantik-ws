<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * NotifikasiPenggunaFixture
 *
 */
class NotifikasiPenggunaFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'notifikasi_pengguna';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'grup_notifikasi' => ['type' => 'string', 'length' => 100, 'default' => null, 'null' => false, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'pesan' => ['type' => 'string', 'length' => 500, 'default' => null, 'null' => false, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'pengguna_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'dibuat_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'tgl_dibuat' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'tgl_diubah' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'diubah_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'notifikasi_pengguna_grup_notifikasi' => ['type' => 'index', 'columns' => ['grup_notifikasi'], 'length' => []],
            'notifikasi_pengguna_pengguna_id' => ['type' => 'index', 'columns' => ['pengguna_id'], 'length' => []],
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
            'grup_notifikasi' => 'Lorem ipsum dolor sit amet',
            'pesan' => 'Lorem ipsum dolor sit amet',
            'pengguna_id' => 1,
            'dibuat_oleh' => 'Lorem ipsum dolor sit a',
            'tgl_dibuat' => 1501264601,
            'tgl_diubah' => 1501264601,
            'diubah_oleh' => 'Lorem ipsum dolor sit a'
        ],
    ];
}
