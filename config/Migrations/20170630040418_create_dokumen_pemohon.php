<?php

use Phinx\Migration\AbstractMigration;

class CreateDokumenPemohon extends AbstractMigration
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
        ### BEGIN - Create Table dokumen_pemohon ###
        $table = $this->table('dokumen_pemohon');

        $table->addColumn('jenis_dokumen_id', 'biginteger', [
            'default' => null,
            'null' => false
        ])->addIndex(['jenis_dokumen_id']);

        $table->addColumn('pemohon_id', 'biginteger', [
            'default' => null,
            'null' => false
        ])->addIndex(['pemohon_id']);

        $table->addColumn('no_dokumen', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => false
        ])->addIndex(['no_dokumen']);

        $table->addColumn('lokasi_dokumen', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false
        ]);

        $table->addColumn('awal_berlaku', 'date', [
            'default' => null,
            'null' => true
        ]);

        $table->addColumn('akhir_berlaku', 'date', [
            'default' => null,
            'null' => true
        ]);

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
        ### BEGIN - Create Table dokumen_pemohon ###

        ### BEGIN - Alter Table persyaratan ###
        $table = $this->table('persyaratan');

        $table->changeColumn('no_dokumen', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => true
        ])->addIndex(['no_dokumen']);

        $table->changeColumn('lokasi_dokumen', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true
        ]);

        $table->update();
        ### END - Alter Table persyaratan ###
    }
}
