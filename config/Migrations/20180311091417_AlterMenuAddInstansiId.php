<?php
use Migrations\AbstractMigration;

class AlterMenuAddInstansiId extends AbstractMigration
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
        $table = $this->table('menu');
        $table->addColumn('instansi_id', 'biginteger', [
            'default' => null,
            'null' => true
        ])->addIndex('instansi_id');
        $table->update();
    }
}
