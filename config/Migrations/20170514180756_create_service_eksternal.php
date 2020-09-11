<?php

use Phinx\Migration\AbstractMigration;

class CreateServiceEksternal extends AbstractMigration
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
        $table = $this->table('service_eksternal');

        $table->addColumn('nama', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => false
        ])->addIndex(['nama']);

        $table->addColumn('deskripsi', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true
        ]);

        $table->addColumn('base_url', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false
        ]);

        $table->addColumn('tipe_otentikasi', 'string', [
            'default' => 'Basic Authentication',
            'limit' => 50,
            'null' => false
        ]);

        $table->addColumn('username', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => true
        ]);

        $table->addColumn('password', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => true
        ]);

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
