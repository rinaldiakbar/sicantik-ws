<?php
use Migrations\AbstractMigration;

class AddWsUrlToUnit extends AbstractMigration
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
        $table = $this->table('unit');
        $table->addColumn('ws_url', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('instansi_code', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
