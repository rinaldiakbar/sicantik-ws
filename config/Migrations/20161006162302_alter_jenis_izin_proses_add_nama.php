<?php

use Phinx\Migration\AbstractMigration;

class AlterJenisIzinProsesAddNama extends AbstractMigration
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
        $table
            ->addColumn('nama_proses', 'string', [
                'default' => null,
                'limit' => 25,
                'null' => true
            ]);
        $table->update();

        $table = $this->table('proses_permohonan');
        $table
            ->addColumn('template_data_id', 'integer', [
                'default' => null,
                'limit' => 20,
                'null' => true
            ])->addIndex('template_data_id');
        $table->update();
    }
}
