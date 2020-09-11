<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * LaporanPermasalahanFixture
 *
 */
class LaporanPermasalahanFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'laporan_permasalahan';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'instansi_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_labels' => ['type' => 'text', 'length' => null, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null],
        'del' => ['type' => 'integer', 'length' => 5, 'default' => '0', 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'dibuat_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'tgl_dibuat' => ['type' => 'date', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'diubah_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'tgl_diubah' => ['type' => 'date', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'kategori' => ['type' => 'string', 'length' => 100, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'permasalahan' => ['type' => 'string', 'length' => 100000, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'tanggapan' => ['type' => 'string', 'length' => 100000, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'status' => ['type' => 'string', 'length' => 100, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'source' => ['type' => 'string', 'length' => 12, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'c_laporan_permasalahan_instansi_id' => ['type' => 'index', 'columns' => ['instansi_id'], 'length' => []],
            'c_laporan_permasalahan_data_labels' => ['type' => 'index', 'columns' => ['data_labels'], 'length' => []],
            'c_laporan_permasalahan_del' => ['type' => 'index', 'columns' => ['del'], 'length' => []],
            'c_laporan_permasalahan_dibuat_oleh' => ['type' => 'index', 'columns' => ['dibuat_oleh'], 'length' => []],
            'c_laporan_permasalahan_tgl_dibuat' => ['type' => 'index', 'columns' => ['tgl_dibuat'], 'length' => []],
            'c_laporan_permasalahan_diubah_oleh' => ['type' => 'index', 'columns' => ['diubah_oleh'], 'length' => []],
            'c_laporan_permasalahan_tgl_diubah' => ['type' => 'index', 'columns' => ['tgl_diubah'], 'length' => []],
            'c_laporan_permasalahan_kategori' => ['type' => 'index', 'columns' => ['kategori'], 'length' => []],
            'c_laporan_permasalahan_permasalahan' => ['type' => 'index', 'columns' => ['permasalahan'], 'length' => []],
            'c_laporan_permasalahan_tanggapan' => ['type' => 'index', 'columns' => ['tanggapan'], 'length' => []],
            'c_laporan_permasalahan_status' => ['type' => 'index', 'columns' => ['status'], 'length' => []],
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
            'data_labels' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'del' => 1,
            'dibuat_oleh' => 'Lorem ipsum dolor sit a',
            'tgl_dibuat' => '2017-08-26',
            'diubah_oleh' => 'Lorem ipsum dolor sit a',
            'tgl_diubah' => '2017-08-26',
            'kategori' => 'Lorem ipsum dolor sit amet',
            'permasalahan' => 'Lorem ipsum dolor sit amet',
            'tanggapan' => 'Lorem ipsum dolor sit amet',
            'status' => 'Lorem ipsum dolor sit amet',
            'source' => 'Lorem ipsu'
        ],
    ];
}
