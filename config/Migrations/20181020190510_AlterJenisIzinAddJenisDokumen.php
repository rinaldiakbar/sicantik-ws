<?php
use Migrations\AbstractMigration;

class AlterJenisIzinAddJenisDokumen extends AbstractMigration
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
        $table = $this->table('jenis_izin');
        $table->addColumn('jenis_dokumen_id', 'biginteger', [
            'default' => null,
            'null' => true
        ])->addIndex('jenis_dokumen_id');
        $table->update();
    }
}
