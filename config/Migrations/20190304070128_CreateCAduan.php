<?php
use Migrations\AbstractMigration;

class CreateCAduan extends AbstractMigration
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
        $table = $this->table('c_aduan');
        $table->addColumn('instansi_id', 'biginteger', [
            'default' => null,
            'null' => false,
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
            'null' => true,
        ]);
        $table->addColumn('tgl_dibuat', 'date', [
            'default' => null,
            'null' => true,
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
        $table->addColumn('kategori', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => true,
        ]);
        $table->addColumn('aduan', 'string', [
            'default' => null,
            'limit' => 1000,
            'null' => false,
        ]);
        $table->addColumn('status', 'string', [
            'default' => null,
            'limit' => 10,
            'null' => true,
        ]);
        $table->addColumn('penyelesaian', 'string', [
            'default' => null,
            'limit' => 1000,
            'null' => false,
        ]);
        $table->addColumn('tgl_aduan', 'date', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('tgl_penyelesaian', 'date', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('penanggung_jawab', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false,
        ]);
        $table->addIndex('data_labels');
        $table->addIndex('del');
        $table->addIndex('dibuat_oleh');
        $table->addIndex('diubah_oleh');
        $table->addIndex('instansi_id');
        $table->addIndex('tgl_dibuat');
        $table->addIndex('tgl_diubah');

        $table->create();

        $table = $this->table('c_aduan_lampiran');
        $table->addColumn('c_aduan_id', 'biginteger', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('instansi_id', 'biginteger', [
            'default' => null,
            'null' => false,
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
            'null' => true,
        ]);
        $table->addColumn('tgl_dibuat', 'date', [
            'default' => null,
            'null' => true,
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
        $table->addColumn('file_lampiran', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('keterangan', 'string', [
            'default' => null,
            'limit' => 1000,
            'null' => false,
        ]);
        
        $table->addIndex('data_labels');
        $table->addIndex('del');
        $table->addIndex('dibuat_oleh');
        $table->addIndex('diubah_oleh');
        $table->addIndex('instansi_id');
        $table->addIndex('tgl_dibuat');
        $table->addIndex('tgl_diubah');

        $table->create();

        $table = $this->table('c_aduan_komentar');
        $table->addColumn('c_aduan_id', 'biginteger', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('instansi_id', 'biginteger', [
            'default' => null,
            'null' => false,
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
            'null' => true,
        ]);
        $table->addColumn('tgl_dibuat', 'date', [
            'default' => null,
            'null' => true,
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
        $table->addColumn('file_lampiran', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('komentar', 'string', [
            'default' => null,
            'limit' => 1000,
            'null' => false,
        ]);
        $table->addColumn('pengguna', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        
        $table->addIndex('data_labels');
        $table->addIndex('del');
        $table->addIndex('dibuat_oleh');
        $table->addIndex('diubah_oleh');
        $table->addIndex('instansi_id');
        $table->addIndex('tgl_dibuat');
        $table->addIndex('tgl_diubah');

        $table->create();
    }
}
