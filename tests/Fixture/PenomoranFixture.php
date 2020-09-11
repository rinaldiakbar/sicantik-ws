<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PenomoranFixture
 *
 */
class PenomoranFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'penomoran';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'format' => ['type' => 'string', 'length' => 50, 'default' => null, 'null' => false, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'deskripsi' => ['type' => 'string', 'length' => 255, 'default' => null, 'null' => false, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'instansi_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'unit_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'jenis_izin_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'dibuat_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'tgl_dibuat' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'tgl_diubah' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'diubah_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'penomoran_format' => ['type' => 'index', 'columns' => ['format'], 'length' => []],
            'penomoran_deskripsi' => ['type' => 'index', 'columns' => ['deskripsi'], 'length' => []],
            'penomoran_instansi_id' => ['type' => 'index', 'columns' => ['instansi_id'], 'length' => []],
            'penomoran_unit_id' => ['type' => 'index', 'columns' => ['unit_id'], 'length' => []],
            'penomoran_jenis_izin_id' => ['type' => 'index', 'columns' => ['jenis_izin_id'], 'length' => []],
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
            'format' => 'Lorem ipsum dolor sit amet',
            'deskripsi' => 'Lorem ipsum dolor sit amet',
            'instansi_id' => 1,
            'unit_id' => 1,
            'jenis_izin_id' => 1,
            'dibuat_oleh' => 'Lorem ipsum dolor sit a',
            'tgl_dibuat' => 1479549376,
            'tgl_diubah' => 1479549376,
            'diubah_oleh' => 'Lorem ipsum dolor sit a'
        ],
    ];
}
