<?php

use Phinx\Migration\AbstractMigration;

class CreatePenomoranDetail extends AbstractMigration
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
        // Drop column from penomoran
        $table = $this->table('penomoran');
        $table->removeColumn('jenis_izin_id')->removeIndex('jenis_izin_id');
        $table->removeColumn('unit_id')->removeIndex('unit_id');


        // Create Table penomoran_detail
        $table = $this->table('penomoran_detail');

        $table->addColumn('penomoran_id', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false
        ])->addIndex(['penomoran_id']);

        $table->addColumn('unit_id', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false
        ])->addIndex(['unit_id']);

        $table->addColumn('no_terakhir', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false
        ])->addIndex(['no_terakhir']);

        $table->create();
    }
}
