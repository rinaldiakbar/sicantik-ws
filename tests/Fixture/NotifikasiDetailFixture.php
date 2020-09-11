<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * NotifikasiDetailFixture
 *
 */
class NotifikasiDetailFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'notifikasi_detail';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'notifikasi_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'daftar_proses_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'tipe' => ['type' => 'string', 'length' => 10, 'default' => 'sms', 'null' => false, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'format_pesan' => ['type' => 'string', 'length' => 10000, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'tipe_penerima' => ['type' => 'string', 'length' => 10, 'default' => 'pemohon', 'null' => false, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'jabatan_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        '_indexes' => [
            'notifikasi_detail_notifikasi_id' => ['type' => 'index', 'columns' => ['notifikasi_id'], 'length' => []],
            'notifikasi_detail_daftar_proses_id' => ['type' => 'index', 'columns' => ['daftar_proses_id'], 'length' => []],
            'notifikasi_detail_tipe' => ['type' => 'index', 'columns' => ['tipe'], 'length' => []],
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
            'notifikasi_id' => 1,
            'daftar_proses_id' => 1,
            'tipe' => 'Lorem ip',
            'format_pesan' => 'Lorem ipsum dolor sit amet',
            'tipe_penerima' => 'Lorem ip',
            'jabatan_id' => 1
        ],
    ];
}
