<?php
use Migrations\AbstractMigration;

class CreateRestServices extends AbstractMigration
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
        $table = $this->table('rest_services');

        $table->addColumn('datatabel_id', 'biginteger', [
            'default' => null,
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
    }
}
