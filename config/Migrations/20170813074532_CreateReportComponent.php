<?php
use Migrations\AbstractMigration;

class CreateReportComponent extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        ## BEGIN - Tabel report_components ##
        $table = $this->table('report_components');

        $table->addColumn('jenis_izin_id', 'biginteger', [
            'default' => null,
            'null' => false
        ])->addIndex(['jenis_izin_id']);

        $table->addColumn('instansi_id', 'biginteger', [
            'default' => null,
            'null' => false
        ])->addIndex(['instansi_id']);

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
        ## END - Tabel report_components ##


        ## BEGIN - Table report_component_details ##
        $table = $this->table('report_component_details');

        $table->addColumn('report_component_id', 'biginteger', [
            'default' => null,
            'null' => false
        ])->addIndex(['report_component_id']);

        $table->addColumn('daftar_proses_id', 'biginteger', [
            'default' => null,
            'null' => false
        ])->addIndex(['daftar_proses_id']);

        $table->addColumn('pegawai_id', 'biginteger', [
            'default' => null,
            'null' => true
        ])->addIndex(['pegawai_id']);

        $table->create();
        ## END - Table report_component_details ##


        ## BEGIN - Alter Table proses_permohonan ##
        $table = $this->table('proses_permohonan');

        $table->addColumn('daftar_proses_id', 'biginteger', [
            'default' => null,
            'null' => true
        ])->addIndex('daftar_proses_id');

        $table->update();
        ## END - Alter Table proses_permohonan ##
    }
}
