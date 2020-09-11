<?php
use Migrations\AbstractMigration;

class CreateOssChecklist extends AbstractMigration
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
        $table = $this->table('oss_checklist');
        $table->addColumn('nib_id', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('oss_id', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('kd_izin', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('kd_dokumen', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('nama_izin', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('instansi', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('instansi_id', 'biginteger', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('jenis_izin_id', 'biginteger', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('flag_checklist', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('last_update', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('status_send', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);        
        $table->create();
    }
}
