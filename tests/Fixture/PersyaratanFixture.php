<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PersyaratanFixture
 *
 */
class PersyaratanFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'persyaratan';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'permohonan_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'persyaratan_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => true, 'comment' => 'Terisi jika dokumen pendukung berasal dari surat yang sudah didefinisikan sebelumnya', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'keterangan' => ['type' => 'string', 'length' => 50, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'lokasi_dokumen' => ['type' => 'string', 'length' => 50, 'default' => null, 'null' => true, 'comment' => 'Path dimana dokumen tersimpan dalam server', 'precision' => null, 'fixed' => null],
        'awal_berlaku' => ['type' => 'date', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'akhir_berlaku' => ['type' => 'date', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'no_dokumen' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'terpenuhi' => ['type' => 'integer', 'length' => 5, 'default' => '0', 'null' => true, 'comment' => 'null: belum terpenuhi; 1 : terpenuhi', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'dibuat_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'tgl_dibuat' => ['type' => 'date', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'diubah_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'tgl_diubah' => ['type' => 'date', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
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
            'permohonan_id' => 1,
            'persyaratan_id' => 1,
            'keterangan' => 'Lorem ipsum dolor sit amet',
            'lokasi_dokumen' => 'Lorem ipsum dolor sit amet',
            'awal_berlaku' => '2016-06-18',
            'akhir_berlaku' => '2016-06-18',
            'no_dokumen' => 'Lorem ipsum dolor sit a',
            'terpenuhi' => 1,
            'dibuat_oleh' => 'Lorem ipsum dolor sit a',
            'tgl_dibuat' => '2016-06-18',
            'diubah_oleh' => 'Lorem ipsum dolor sit a',
            'tgl_diubah' => '2016-06-18'
        ],
    ];
}
