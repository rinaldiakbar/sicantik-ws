<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PerusahaanFixture
 *
 */
class PerusahaanFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'perusahaan';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'nama_perusahaan' => ['type' => 'string', 'length' => 100, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'fixed' => null],
        'npwp' => ['type' => 'string', 'length' => 100, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'no_register' => ['type' => 'string', 'length' => 100, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'jenis_usaha_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'bidang_usaha_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'jenis_perusahaan' => ['type' => 'string', 'length' => 100, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'jumlah_pegawai' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'nilai_investasi' => ['type' => 'float', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'no_tlp' => ['type' => 'string', 'length' => 15, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'fax' => ['type' => 'string', 'length' => 15, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'email' => ['type' => 'string', 'length' => 50, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'alamat' => ['type' => 'string', 'length' => 100, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'desa_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'kecamatan_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'kabupaten_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'provinsi_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'kode_pos' => ['type' => 'string', 'length' => 10, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
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
            'nama_perusahaan' => 'Lorem ipsum dolor sit amet',
            'npwp' => 'Lorem ipsum dolor sit amet',
            'no_register' => 'Lorem ipsum dolor sit amet',
            'jenis_usaha_id' => 1,
            'bidang_usaha_id' => 1,
            'jenis_perusahaan' => 'Lorem ipsum dolor sit amet',
            'jumlah_pegawai' => 1,
            'nilai_investasi' => 1,
            'no_tlp' => 'Lorem ipsum d',
            'fax' => 'Lorem ipsum d',
            'email' => 'Lorem ipsum dolor sit amet',
            'alamat' => 'Lorem ipsum dolor sit amet',
            'desa_id' => 1,
            'kecamatan_id' => 1,
            'kabupaten_id' => 1,
            'provinsi_id' => 1,
            'kode_pos' => 'Lorem ip',
            'dibuat_oleh' => 'Lorem ipsum dolor sit a',
            'tgl_dibuat' => '2016-06-18',
            'diubah_oleh' => 'Lorem ipsum dolor sit a',
            'tgl_diubah' => '2016-06-18'
        ],
    ];
}
