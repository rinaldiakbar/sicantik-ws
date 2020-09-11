<?php

use Phinx\Migration\AbstractMigration;

class CreatePenomoran extends AbstractMigration
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
        $table = $this->table('penomoran');

        $table->addColumn('format', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => false
        ])->addIndex(['format']);

        $table->addColumn('deskripsi', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false
        ])->addIndex(['deskripsi']);

        $table->addColumn('no_terakhir', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false
        ])->addIndex(['no_terakhir']);

        $table->addColumn('instansi_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false
        ])->addIndex(['instansi_id']);

        $table->addColumn('unit_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true
        ])->addIndex(['unit_id']);

        $table->addColumn('jenis_izin_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true
        ])->addIndex(['jenis_izin_id']);

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
