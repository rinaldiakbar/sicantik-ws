<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * JabatanFixture
 *
 */
class JabatanFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'jabatan';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'instansi_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_labels' => ['type' => 'text', 'length' => null, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null],
        'del' => ['type' => 'integer', 'length' => 5, 'default' => '0', 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'dibuat_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'tgl_dibuat' => ['type' => 'date', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'diubah_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'tgl_diubah' => ['type' => 'date', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'jabatan' => ['type' => 'string', 'length' => 30, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'nama_jabatan' => ['type' => 'string', 'length' => 200, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'c_jabatan_instansi_id' => ['type' => 'index', 'columns' => ['instansi_id'], 'length' => []],
            'c_jabatan_data_labels' => ['type' => 'index', 'columns' => ['data_labels'], 'length' => []],
            'c_jabatan_del' => ['type' => 'index', 'columns' => ['del'], 'length' => []],
            'c_jabatan_dibuat_oleh' => ['type' => 'index', 'columns' => ['dibuat_oleh'], 'length' => []],
            'c_jabatan_tgl_dibuat' => ['type' => 'index', 'columns' => ['tgl_dibuat'], 'length' => []],
            'c_jabatan_diubah_oleh' => ['type' => 'index', 'columns' => ['diubah_oleh'], 'length' => []],
            'c_jabatan_tgl_diubah' => ['type' => 'index', 'columns' => ['tgl_diubah'], 'length' => []],
            'c_jabatan_jabatan' => ['type' => 'index', 'columns' => ['jabatan'], 'length' => []],
            'c_jabatan_nama_jabatan' => ['type' => 'index', 'columns' => ['nama_jabatan'], 'length' => []],
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
            'tgl_dibuat' => '2017-05-11',
            'diubah_oleh' => 'Lorem ipsum dolor sit a',
            'tgl_diubah' => '2017-05-11',
            'jabatan' => 'Lorem ipsum dolor sit amet',
            'nama_jabatan' => 'Lorem ipsum dolor sit amet'
        ],
    ];
}
