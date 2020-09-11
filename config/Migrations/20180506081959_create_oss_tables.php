<?php

use Phinx\Migration\AbstractMigration;

class CreateOssTables extends AbstractMigration
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
        ## Alter Jenis Izin ##
        $table = $this->table('jenis_izin');
        $table
            ->changeColumn('oss_id', 'string', [
                'default' => null,
                'limit' => 25,
                'null' => true,
            ])
            ->renameColumn('oss_id', 'kode_oss');
        $table->update();

        ## BEGIN - Create Table data_sinc ##
        $table = $this->table('data_sinc');

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

        $table->addColumn('key', 'string', [
            'default' => null,
            'limit' => 1000,
            'null' => false,
        ])->addIndex(['key']);

        $table->addColumn('keterangan', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);

        $table->addColumn('parent_id', 'biginteger', [
            'default' => null,
            'null' => true,
        ])->addIndex(['parent_id']);

        $table->addColumn('index', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false,
        ])->addIndex(['index']);

        $table->create();

        ## BEGIN - Create Table data_sinc_detail ##
        $table = $this->table('data_sinc_detail');

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

        $table->addColumn('data_sinc_id', 'biginteger', [
            'default' => null,
            'null' => true,
        ])->addIndex(['data_sinc_id']);

        $table->addColumn('data_kolom_id', 'biginteger', [
            'default' => null,
            'null' => false,
        ])->addIndex(['data_kolom_id']);

        $table->addColumn('oss_type_kolom', 'string', [
            'default' => null,
            'limit' => 5,
            'null' => false,
        ])->addIndex(['oss_type_kolom']);

        $table->addColumn('oss_kolom', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => true,
        ])->addIndex(['oss_kolom']);

        $table->create();

        ## BEGIN - Create Table nib ##
        $table = $this->table('nib');

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

        $table->addColumn('alamat_investasi', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);

        $table->addColumn('daerah_investasi', 'string', [
            'default' => null,
            'limit' => 10,
            'null' => true,
        ]);

        $table->addColumn('status_penanaman_modal', 'string', [
            'default' => null,
            'limit' => 2,
            'null' => true,
        ])->addIndex(['status_penanaman_modal']);

        $table->addColumn('status_badan_hukum', 'string', [
            'default' => null,
            'limit' => 2,
            'null' => true,
        ])->addIndex(['status_badan_hukum']);

        $table->addColumn('jangka_waktu', 'datetime', [
            'default' => null,
            'null' => true,
        ]);

        $table->addColumn('oss_id', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => true,
        ])->addIndex(['oss_id']);

        $table->addColumn('nib', 'string', [
            'default' => null,
            'limit' => 14,
            'null' => false,
        ])->addIndex(['nib']);

        $table->create();
    }
}
