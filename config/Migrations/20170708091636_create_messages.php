<?php

use Phinx\Migration\AbstractMigration;

class CreateMessages extends AbstractMigration
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
        ### BEGIN - Create Table gateway_users ###
        $table = $this->table('gateway_users');

        $table->addColumn('username', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => false
        ])->addIndex(['username']);

        $table->addColumn('password', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false
        ]);

        $table->addColumn('is_active', 'integer', [
            'default' => 1,
            'limit' => 1,
            'null' => false
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
        ### BEGIN - Create Table gateway_users ###
        
        ### BEGIN - Create Table messages ###
        $table = $this->table('messages');

        $table->addColumn('gateway_user_id', 'biginteger', [
            'default' => null,
            'null' => false
        ])->addIndex(['gateway_user_id']);

        $table->addColumn('recipient_no', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => false
        ]);

        $table->addColumn('body', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false
        ]);

        $table->addColumn('status', 'string', [
            'default' => 'NEW', // NEW, FETCHED, SENT, FAILED, DELIVERED
            'limit' => 10,
            'null' => false
        ])->addIndex(['status']);

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
        ### BEGIN - Create Table messages ###
    }
}
