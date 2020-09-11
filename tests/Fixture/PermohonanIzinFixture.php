<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PermohonanIzinFixture
 *
 */
class PermohonanIzinFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'permohonan_izin';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'no_permohonan' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'fixed' => null],
        'pemohon_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'perusahaan_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'keterangan' => ['type' => 'string', 'length' => 100, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'jenis_permohonan' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'comment' => 'Baru; Perpanjang; Daftar Ulang', 'precision' => null, 'fixed' => null],
        'izin_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'no_izin_lama' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'comment' => 'No dari izin yang sudah ada, diisi jika perpanjang atau daftar ulang', 'precision' => null, 'fixed' => null],
        'tgl_pengajuan' => ['type' => 'date', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'tgl_selesai' => ['type' => 'date', 'length' => null, 'default' => null, 'null' => true, 'comment' => 'Tanggal penetapan atau penolakan izin', 'precision' => null],
        'proses_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'status' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'comment' => 'Draft: jika pengajuan dari online; Progress; Selesai; Ditolak', 'precision' => null, 'fixed' => null],
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
            'no_permohonan' => 'Lorem ipsum dolor sit a',
            'pemohon_id' => 1,
            'perusahaan_id' => 1,
            'keterangan' => 'Lorem ipsum dolor sit amet',
            'jenis_permohonan' => 'Lorem ipsum dolor sit a',
            'izin_id' => 1,
            'no_izin_lama' => 'Lorem ipsum dolor sit a',
            'tgl_pengajuan' => '2016-06-18',
            'tgl_selesai' => '2016-06-18',
            'proses_id' => 1,
            'status' => 'Lorem ipsum dolor sit a',
            'dibuat_oleh' => 'Lorem ipsum dolor sit a',
            'tgl_dibuat' => '2016-06-18',
            'diubah_oleh' => 'Lorem ipsum dolor sit a',
            'tgl_diubah' => '2016-06-18'
        ],
    ];
}
