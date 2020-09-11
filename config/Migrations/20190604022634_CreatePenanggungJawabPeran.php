<?php
use Migrations\AbstractMigration;

class CreatePenanggungJawabPeran extends AbstractMigration
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
        $table = $this->table('penanggung_jawab_peran');
        $table->addColumn('peran_id', 'biginteger', [
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
