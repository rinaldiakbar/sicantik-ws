<?php
use Migrations\AbstractMigration;

class AddNonIzinToJenisIzin extends AbstractMigration
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
        $table->addColumn('non_izin', 'boolean', [
            'default' => false,
            'null' => true,
        ]);
        $table->addColumn('bidang', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => true,
        ]);
        $table->update();
    }
}
