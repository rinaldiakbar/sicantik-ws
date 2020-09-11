<?php

use Phinx\Migration\AbstractMigration;

class AlterPermohonanIzinAddProyek extends AbstractMigration
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
        $table = $this->table('permohonan_izin');

        $table->addColumn('jenis_proyek_id', 'biginteger', [
            'default' => null,
            'null' => true
        ])->addIndex('jenis_proyek_id');

        $table->addColumn('target_pad', 'decimal', [
            'default' => null,
            'null' => true
        ])->addIndex('target_pad');

        $table->addColumn('nilai_investasi', 'decimal', [
            'default' => null,
            'null' => true
        ])->addIndex('nilai_investasi');

        $table->addColumn('jumlah_tenaga_kerja', 'integer', [
            'default' => null,
            'null' => true
        ])->addIndex('jumlah_tenaga_kerja');

        $table->update();
    }
}
