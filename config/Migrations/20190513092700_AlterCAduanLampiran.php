<?php
use Migrations\AbstractMigration;

class AlterCAduanLampiran extends AbstractMigration
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
        $table = $this->table('c_aduan_lampiran');
        $table->changeColumn('instansi_id', 'biginteger', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
