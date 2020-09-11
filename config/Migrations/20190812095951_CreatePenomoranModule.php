<?php
use Migrations\AbstractMigration;

class CreatePenomoranModule extends AbstractMigration
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
        $table = $this->table('penomoran_module');
        $table->addColumn('instansi_id', 'biginteger', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('data_labels', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('del', 'integer', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('dibuat_oleh', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => false,
        ]);
        $table->addColumn('tgl_dibuat', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('diubah_oleh', 'string', [
            'default' => null,
            'limit' => 25,
        ]);
        $table->addColumn('tgl_diubah', 'date', [
            'default' => null,
        ]);
        $table->addColumn('module', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('penomoran_id', 'biginteger', [
            'null' => false,
        ]);
        $table->create();
    }
}
