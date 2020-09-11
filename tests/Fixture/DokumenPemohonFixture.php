<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DokumenPemohonFixture
 *
 */
class DokumenPemohonFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'dokumen_pemohon';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'jenis_dokumen_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'no_dokumen' => ['type' => 'string', 'length' => 50, 'default' => null, 'null' => false, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'lokasi_dokumen' => ['type' => 'string', 'length' => 255, 'default' => null, 'null' => false, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'awal_berlaku' => ['type' => 'date', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'akhir_berlaku' => ['type' => 'date', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'dibuat_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'tgl_dibuat' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'tgl_diubah' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'diubah_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'dokumen_pemohon_jenis_dokumen_id' => ['type' => 'index', 'columns' => ['jenis_dokumen_id'], 'length' => []],
            'dokumen_pemohon_no_dokumen' => ['type' => 'index', 'columns' => ['no_dokumen'], 'length' => []],
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
            'jenis_dokumen_id' => 1,
            'no_dokumen' => 'Lorem ipsum dolor sit amet',
            'lokasi_dokumen' => 'Lorem ipsum dolor sit amet',
            'awal_berlaku' => '2017-06-30',
            'akhir_berlaku' => '2017-06-30',
            'dibuat_oleh' => 'Lorem ipsum dolor sit a',
            'tgl_dibuat' => 1498796401,
            'tgl_diubah' => 1498796401,
            'diubah_oleh' => 'Lorem ipsum dolor sit a'
        ],
    ];
}
