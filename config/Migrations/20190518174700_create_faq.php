<?php

use Phinx\Migration\AbstractMigration;

class CreateFaq extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        /** FAQ Category **/
        $table = $this->table('faq_category');

        $table->addColumn('nama', 'string', [
            'limit' => 50,
            'null' => false
        ]);

        $table->addColumn('deskripsi', 'string', [
            'limit' => 500,
            'null' => true
        ]);

        $table->addColumn('no_urut', 'integer', [
            'default' => null,
            'limit' => 10,
            'null' => true,
        ])->addIndex(['no_urut']);

        $table->addColumn('is_active', 'integer', [
            'default' => 1,
            'limit' => 1,
            'null' => false
        ])->addIndex(['is_active']);

        $table
            ->addColumn('instansi_id', 'biginteger', [
                'default' => null,
                'null' => true,
            ])
            ->addForeignKey('instansi_id', 'unit', 'id', ['delete' => 'RESTRICT', 'update' => 'NO_ACTION']);

        $table->addColumn('dibuat_oleh', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => true
        ]);

        $table->addColumn('tgl_dibuat', 'datetime', [
            'default' => null,
            'limit' => 20,
            'null' => true
        ]);

        $table->addColumn('tgl_diubah', 'datetime', [
            'default' => null,
            'limit' => 20,
            'null' => true
        ]);

        $table->addColumn('diubah_oleh', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => true
        ]);

        $table->create();

        /** FAQ **/
        $table = $this->table('faq');

        $table
            ->addColumn('faq_category_id', 'integer', [
                'null' => false,
            ])
            ->addForeignKey('faq_category_id', 'faq_category', 'id', ['delete' => 'RESTRICT', 'update' => 'NO_ACTION'])
            ->addIndex(['faq_category_id']);

        $table
            ->addColumn('instansi_id', 'biginteger', [
                'default' => null,
                'null' => true,
            ])
            ->addForeignKey('instansi_id', 'unit', 'id', ['delete' => 'RESTRICT', 'update' => 'NO_ACTION']);

        $table->addColumn('pertanyaan', 'string', [
            'limit' => 1000,
            'null' => false
        ])->addIndex(['pertanyaan']);

        $table->addColumn('jawaban', 'text', [
            'null' => false
        ]);

        $table->addColumn('no_urut', 'integer', [
            'default' => null,
            'limit' => 10,
            'null' => true,
        ])->addIndex(['no_urut']);

        $table->addColumn('file_lampiran', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true
        ]);

        $table->addColumn('is_active', 'integer', [
            'default' => 1,
            'limit' => 1,
            'null' => false
        ])->addIndex(['is_active']);

        $table->addColumn('dibuat_oleh', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => true
        ]);

        $table->addColumn('tgl_dibuat', 'datetime', [
            'default' => null,
            'limit' => 20,
            'null' => true
        ]);

        $table->addColumn('tgl_diubah', 'datetime', [
            'default' => null,
            'limit' => 20,
            'null' => true
        ]);

        $table->addColumn('diubah_oleh', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => true
        ]);

        $table->create();
    }
}
