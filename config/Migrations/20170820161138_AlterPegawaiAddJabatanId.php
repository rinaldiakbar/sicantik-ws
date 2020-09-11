<?php
use Migrations\AbstractMigration;

class AlterPegawaiAddJabatanId extends AbstractMigration
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
        $table = $this->table('pegawai');
        $table
            ->addColumn('jabatan_id', 'biginteger', [
                'default' => null,
                'null' => true
            ])->addIndex('jabatan_id');
        $table->removeColumn('jabatan');
        $table->update();
    }
}
