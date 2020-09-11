<?php

use Phinx\Migration\AbstractMigration;

class CreateJenisIzinProses extends AbstractMigration
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
        $table = $this->table('jenis_izin_proses');

        $table->addColumn('jenis_pengajuan_id', 'integer', [
            'default' => null,
            'limit' => 20,
            'null' => false
        ])->addIndex(['jenis_pengajuan_id']);

        $table->addColumn('daftar_proses_id', 'integer', [
            'default' => null,
            'limit' => 20,
            'null' => false
        ])->addIndex(['daftar_proses_id']);

        $table->addColumn('tautan', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => true
        ]);

        $table->addColumn('form_id', 'integer', [
            'default' => null,
            'limit' => 20,
            'null' => true
        ])->addIndex(['form_id']);

        $table->addColumn('template_data_id', 'integer', [
            'default' => null,
            'limit' => 20,
            'null' => true
        ])->addIndex(['template_data_id']);

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
