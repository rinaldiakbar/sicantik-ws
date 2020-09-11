<?php
use Migrations\AbstractMigration;

class AlterRetribusiDetailChangeJumlah extends AbstractMigration
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
        $table = $this->table('retribusi_detail');
        $table->changeColumn('jumlah', 'float', [
            'default' => 0,
            'null' => false
        ]);
        $table->update();
    }
}
