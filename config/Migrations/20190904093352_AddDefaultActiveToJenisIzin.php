<?php
use Migrations\AbstractMigration;

class AddDefaultActiveToJenisIzin extends AbstractMigration
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
        $table->addColumn('default_active', 'boolean', [
            'default' => true
        ]);
        $table->update();
    }
}
