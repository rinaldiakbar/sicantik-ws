<?php

use Phinx\Migration\AbstractMigration;

class CreateRetribusiDetail extends AbstractMigration
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
        $table = $this->table('retribusi_detail');

        $table->addColumn('kode_item', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false
        ])->addIndex(['kode_item']);

        $table->addColumn('nama_item', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false
        ])->addIndex(['nama_item']);

        $table->addColumn('satuan', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => true
        ])->addIndex(['satuan']);

        $table->addColumn('permohonan_izin_id', 'integer', [
            'default' => null,
            'null' => false
        ])->addIndex(['permohonan_izin_id']);

        $table->addColumn('harga', 'float', [
            'default' => 0,
            'null' => false
        ]);

        $table->addColumn('jumlah', 'integer', [
            'default' => 0,
            'null' => false
        ]);

        $table->addColumn('subtotal', 'float', [
            'default' => 0,
            'null' => false
        ]);

        $table->create();
    }
}
