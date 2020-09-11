<?php
use Migrations\AbstractMigration;

class CreateOssCommitment extends AbstractMigration
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
        $table = $this->table('oss_commitment');
        $table->addColumn('instansi_id', 'biginteger', [
            'default' => null,
            'null' => true,
        ])->addIndex(['instansi_id']);

        $table->addColumn('dibuat_oleh', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => true,
        ]);

        $table->addColumn('tgl_dibuat', 'datetime', [
            'default' => null,
            'limit' => 20,
            'null' => true,
        ]);

        $table->addColumn('tgl_diubah', 'datetime', [
            'default' => null,
            'limit' => 20,
            'null' => true,
        ]);

        $table->addColumn('diubah_oleh', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => true,
        ]);

        $table->addColumn('nib_id', 'string', [
            'limit' => 255,
            'null' => false,
        ]);

        $table->addColumn('oss_id', 'string', [
            'limit' => 255,
            'null' => false,
        ]);

        $table->addColumn('kode_izin', 'string', [
            'limit' => 255,
            'null' => false,
        ]);

        $table->addColumn('kode_daerah', 'string', [
            'limit' => 100,
            'null' => false,
        ]);

        $table->addColumn('tipe_dokumen', 'biginteger', [
            'default' => null,
            'null' => true,
        ]);

        $table->addColumn('tgl_terbit_izin', 'datetime', [
            'limit' => 20,
            'null' => false,
        ]);

        $table->addColumn('tgl_berlaku_izin', 'datetime', [
            'default' => null,
            'limit' => 20,
            'null' => true,
        ]);

        $table->addColumn('status', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false,
        ]);

        $table->addColumn('tgl_status', 'datetime', [
            'default' => null,
            'limit' => 20,
            'null' => true,
        ]);

        $table->addColumn('keterangan', 'string', [
            'default' => null,
            'limit' => 10000,
            'null' => false,
        ]);

        $table->create();
    }
}
