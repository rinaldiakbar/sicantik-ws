<?php

use Phinx\Migration\AbstractMigration;

class CreateJabatan extends AbstractMigration
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
        $table = $this->table('jabatan');

        $table->addColumn('jabatan', 'string', [
            'default' => null,
            'limit' => 30,
            'null' => false
        ])->addIndex(['jabatan']);

        $table->addColumn('nama_jabatan', 'string', [
            'default' => null,
            'limit' => 200,
            'null' => true
        ])->addIndex(['nama_jabatan']);

        $table->addColumn('instansi_id', 'biginteger', [
            'default' => null,
            'null' => false
        ])->addIndex('instansi_id');

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
