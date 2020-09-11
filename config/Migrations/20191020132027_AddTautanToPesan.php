<?php
use Migrations\AbstractMigration;

class AddTautanToPesan extends AbstractMigration
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
        $table = $this->table('pesan');
        $table->addColumn('tautan', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true
        ]);
        $table->addColumn('object_id', 'biginteger', [
            'default' => null,
            'null' => true
        ]);
        $table->update();
    }
}
