<?php
use Migrations\AbstractMigration;

class CreatePage extends AbstractMigration
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
        $table = $this->table('page');
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
            'limit' => 20,
            'null' => false,
        ]);
        $table->addColumn('tgl_dibuat', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('diubah_oleh', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => true,
        ]);
        $table->addColumn('tgl_diubah', 'date', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('title', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->create();
        
        $table = $this->table('page_tab');
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
            'limit' => 20,
            'null' => false,
        ]);
        $table->addColumn('tgl_dibuat', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('diubah_oleh', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => true,
        ]);
        $table->addColumn('tgl_diubah', 'date', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('page_id', 'biginteger', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('label', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => false,
        ]);
        $table->addColumn('tab_idx', 'biginteger', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
        
        $table = $this->table('page_content');
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
            'limit' => 20,
            'null' => false,
        ]);
        $table->addColumn('tgl_dibuat', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('diubah_oleh', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => true,
        ]);
        $table->addColumn('tgl_diubah', 'date', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('posisi', 'biginteger', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('webservice', 'string', [
            'default' => null,
            'limit' => 1000,
            'null' => false,
        ]);
        $table->addColumn('type_chart', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => false,
        ]);
        $table->addColumn('tab_idx', 'biginteger', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('page_id', 'biginteger', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('height', 'biginteger', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('width', 'biginteger', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('title', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => false,
        ]);
        $table->create();
    }
}
