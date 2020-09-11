<?php
use Migrations\AbstractMigration;

class AlterJenisProsesAddIsDrop extends AbstractMigration
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
        $table = $this->table('jenis_proses');
        $table->addColumn('is_drop', 'boolean', [
            'default' => false,
            'null' => false,
        ]);
        $table->update();
    }
}
