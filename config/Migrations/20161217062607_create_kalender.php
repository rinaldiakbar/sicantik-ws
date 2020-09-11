<?php

use Phinx\Migration\AbstractMigration;

class CreateKalender extends AbstractMigration
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
        $table = $this->table('kalender');

        $table->addColumn('instansi_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false
        ])->addIndex(['instansi_id']);

        $table->addColumn('tipe', 'string', [
            'default' => 'H',
            'limit' => 2,
            'null' => false
        ])->addIndex(['tipe']);

        $table->addColumn('nama_hari', 'string', [
            'default' => null,
            'limit' => 12,
            'null' => true
        ])->addIndex(['nama_hari']);

        $table->addColumn('idx_hari', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true
        ])->addIndex(['idx_hari']);

        $table->addColumn('tanggal', 'date', [
            'default' => null,
            'null' => true
        ])->addIndex(['tanggal']);

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
