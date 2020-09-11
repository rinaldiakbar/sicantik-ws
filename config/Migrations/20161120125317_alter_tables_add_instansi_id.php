<?php

use Phinx\Migration\AbstractMigration;

class AlterTablesAddInstansiId extends AbstractMigration
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
        $mainTables = [
            'pengguna', 'alur_proses', 'jenis_proses', 'jenis_izin', 'tarif_item', 'permohonan_izin', 'datatabel',
            'form'
        ];

        // Staging
        $customTables = [
            'c_coa', 'c_gangguan', 'c_jurnal_detail', 'c_jurnal_header', 'c_pemohon', 'c_permohonan_izin',
            'c_perusahaan', 'c_template_report', 'c_datatabel1', 'c_test20161103', 'c_test_3', 'c_view_daftar_izin'
        ];

        // Production
        /*$customTables = [
            'c_gangguan', 'c_jurnal_header', 'c_pemohon', 'c_permohonan_izin', 'c_perusahaan','c_view_daftar_izin'
        ];*/

        $tableNames = array_merge($mainTables, $customTables);

        foreach ($tableNames as $tableName) {
            $table = $this->table($tableName);
            $table->addColumn('instansi_id', 'biginteger', [
                'default' => null,
                'null' => true
            ])->addIndex('instansi_id');
            $table->update();
        }
    }
}
