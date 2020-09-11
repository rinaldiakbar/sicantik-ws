<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * QueueJobsFixture
 *
 */
class QueueJobsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'type' => ['type' => 'string', 'length' => 30, 'default' => null, 'null' => false, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'body' => ['type' => 'string', 'length' => 500, 'default' => null, 'null' => false, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'status' => ['type' => 'string', 'length' => 15, 'default' => 'WAITING', 'null' => false, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'priority' => ['type' => 'integer', 'length' => 10, 'default' => '10', 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'delay_time' => ['type' => 'integer', 'length' => 10, 'default' => '0', 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'execute_time' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'dibuat_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'tgl_dibuat' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'tgl_diubah' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'diubah_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'queue_jobs_type' => ['type' => 'index', 'columns' => ['type'], 'length' => []],
            'queue_jobs_status' => ['type' => 'index', 'columns' => ['status'], 'length' => []],
            'queue_jobs_priority' => ['type' => 'index', 'columns' => ['priority'], 'length' => []],
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
            'type' => 'Lorem ipsum dolor sit amet',
            'body' => 'Lorem ipsum dolor sit amet',
            'status' => 'Lorem ipsum d',
            'priority' => 1,
            'delay_time' => 1,
            'execute_time' => 1496584330,
            'dibuat_oleh' => 'Lorem ipsum dolor sit a',
            'tgl_dibuat' => 1496584330,
            'tgl_diubah' => 1496584330,
            'diubah_oleh' => 'Lorem ipsum dolor sit a'
        ],
    ];
}
