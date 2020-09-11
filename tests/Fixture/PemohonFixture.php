<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PemohonFixture
 *
 */
class PemohonFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'pemohon';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'tipe_identitas' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => false, 'comment' => 'KTP; SIM, Passport', 'precision' => null, 'fixed' => null],
        'no_identitas' => ['type' => 'string', 'length' => 100, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'fixed' => null],
        'nama' => ['type' => 'string', 'length' => 100, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'fixed' => null],
        'tempat_lahir' => ['type' => 'string', 'length' => 100, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'tgl_lahir' => ['type' => 'date', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'jenis_kelamin' => ['type' => 'string', 'length' => 2, 'default' => null, 'null' => false, 'comment' => 'L: Laki-laki; P: Perempuan', 'precision' => null, 'fixed' => null],
        'pekerjaan' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'perusahaan_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => true, 'comment' => 'ID Perusahaan dimana orang tersebut bekerja saat mengajukan', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'no_tlp' => ['type' => 'string', 'length' => 15, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'no_hp' => ['type' => 'string', 'length' => 15, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
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
            'tipe_identitas' => 'Lorem ipsum dolor sit a',
            'no_identitas' => 'Lorem ipsum dolor sit amet',
            'nama' => 'Lorem ipsum dolor sit amet',
            'tempat_lahir' => 'Lorem ipsum dolor sit amet',
            'tgl_lahir' => '2016-06-18',
            'jenis_kelamin' => '',
            'pekerjaan' => 'Lorem ipsum dolor sit a',
            'perusahaan_id' => 1,
            'no_tlp' => 'Lorem ipsum d',
            'no_hp' => 'Lorem ipsum d',
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
