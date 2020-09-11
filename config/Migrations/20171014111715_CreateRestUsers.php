<?php
use Migrations\AbstractMigration;

class CreateRestUsers extends AbstractMigration
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
        ### BEGIN - Create Table gateway_users ###
        $table = $this->table('rest_users');

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

        $table->addColumn('instansi_id', 'biginteger', [
            'default' => null,
            'null' => true
        ])->addIndex('instansi_id');

        $table->create();
        ### BEGIN - Create Table gateway_users ###
    }
}
