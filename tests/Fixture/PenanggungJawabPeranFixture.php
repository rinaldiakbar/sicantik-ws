<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PenanggungJawabPeranFixture
 *
 */
class PenanggungJawabPeranFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'penanggung_jawab_peran';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'peran_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'instansi_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_labels' => ['type' => 'text', 'length' => null, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null],
        'del' => ['type' => 'integer', 'length' => 10, 'default' => '0', 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'dibuat_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'tgl_dibuat' => ['type' => 'date', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'diubah_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'tgl_diubah' => ['type' => 'date', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        '_indexes' => [
            'penanggung_jawab_peran_instansi_id' => ['type' => 'index', 'columns' => ['instansi_id'], 'length' => []],
            'penanggung_jawab_peran_data_labels' => ['type' => 'index', 'columns' => ['data_labels'], 'length' => []],
            'penanggung_jawab_peran_del' => ['type' => 'index', 'columns' => ['del'], 'length' => []],
            'penanggung_jawab_peran_dibuat_oleh' => ['type' => 'index', 'columns' => ['dibuat_oleh'], 'length' => []],
            'penanggung_jawab_peran_tgl_dibuat' => ['type' => 'index', 'columns' => ['tgl_dibuat'], 'length' => []],
            'penanggung_jawab_peran_diubah_oleh' => ['type' => 'index', 'columns' => ['diubah_oleh'], 'length' => []],
            'penanggung_jawab_peran_tgl_diubah' => ['type' => 'index', 'columns' => ['tgl_diubah'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'peran_id' => 1,
                'instansi_id' => 1,
                'data_labels' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'del' => 1,
                'dibuat_oleh' => 'Lorem ipsum dolor sit a',
                'tgl_dibuat' => '2019-06-04',
                'diubah_oleh' => 'Lorem ipsum dolor sit a',
                'tgl_diubah' => '2019-06-04'
            ],
        ];
        parent::init();
    }
}
