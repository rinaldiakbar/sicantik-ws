<?php
use Migrations\AbstractMigration;

class CreateOssLampiran extends AbstractMigration
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
        $table = $this->table('oss_lampiran');

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

        $table->addColumn('nama_dokumen', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);

        $table->addColumn('link', 'string', [
            'default' => null,
            'limit' => 1000,
            'null' => true,
        ]);

        $table->create();
    }
}
