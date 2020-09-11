<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FaqFixture
 *
 */
class FaqFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'faq';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'faq_category_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'instansi_id' => ['type' => 'biginteger', 'length' => 20, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'pertanyaan' => ['type' => 'string', 'length' => 1000, 'default' => null, 'null' => false, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'jawaban' => ['type' => 'text', 'length' => null, 'default' => null, 'null' => false, 'collate' => null, 'comment' => null, 'precision' => null],
        'no_urut' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'file_lampiran' => ['type' => 'string', 'length' => 255, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'is_active' => ['type' => 'integer', 'length' => 10, 'default' => '1', 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'dibuat_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'tgl_dibuat' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'tgl_diubah' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'diubah_oleh' => ['type' => 'string', 'length' => 25, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        'search' => ['type' => 'string', 'length' => 500, 'default' => null, 'null' => true, 'collate' => null, 'comment' => null, 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'faq_faq_category_id' => ['type' => 'index', 'columns' => ['faq_category_id'], 'length' => []],
            'faq_pertanyaan' => ['type' => 'index', 'columns' => ['pertanyaan'], 'length' => []],
            'faq_no_urut' => ['type' => 'index', 'columns' => ['no_urut'], 'length' => []],
            'faq_is_active' => ['type' => 'index', 'columns' => ['is_active'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'faq_faq_category_id_fkey' => ['type' => 'foreign', 'columns' => ['faq_category_id'], 'references' => ['faq_category', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
            'faq_instansi_id_fkey' => ['type' => 'foreign', 'columns' => ['instansi_id'], 'references' => ['unit', 'id'], 'update' => 'noAction', 'delete' => 'restrict', 'length' => []],
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
                'faq_category_id' => 1,
                'instansi_id' => 1,
                'pertanyaan' => 'Lorem ipsum dolor sit amet',
                'jawaban' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'no_urut' => 1,
                'file_lampiran' => 'Lorem ipsum dolor sit amet',
                'is_active' => 1,
                'dibuat_oleh' => 'Lorem ipsum dolor sit a',
                'tgl_dibuat' => 1559060711,
                'tgl_diubah' => 1559060711,
                'diubah_oleh' => 'Lorem ipsum dolor sit a',
                'search' => 'Lorem ipsum dolor sit amet'
            ],
        ];
        parent::init();
    }
}
