<?php

use Phinx\Migration\AbstractMigration;

class CreateNotification extends AbstractMigration
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
        ## BEGIN - Tabel notifikasi ##
        $table = $this->table('notifikasi');

        $table->addColumn('jenis_izin_id', 'biginteger', [
            'default' => null,
            'null' => false
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
        ## END - Tabel notifikasi ##


        ## BEGIN - Table notifikasi_detail ##
        $table = $this->table('notifikasi_detail');

        $table->addColumn('notifikasi_id', 'biginteger', [
            'default' => null,
            'null' => false
        ])->addIndex(['notifikasi_id']);

        $table->addColumn('daftar_proses_id', 'biginteger', [
            'default' => null,
            'null' => false
        ])->addIndex(['daftar_proses_id']);

        $table->addColumn('tipe', 'string', [
            'default' => 'sms',
            'limit' => '10',
            'null' => false
        ])->addIndex('tipe');

        $table->addColumn('format_pesan', 'string', [
            'default' => null,
            'limit' => '10000',
            'null' => true
        ]);

        $table->addColumn('tipe_penerima', 'string', [
            'default' => 'pemohon', //pemohon atau jabatan 
            'limit' => '10',
            'null' => false
        ]);

        $table->addColumn('jabatan_id', 'biginteger', [
            'default' => null,
            'limit' => null,
            'null' => true
        ]);

        $table->create();
        ## END - Table notifikasi_detail ##
    }
}
