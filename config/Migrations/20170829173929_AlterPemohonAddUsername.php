<?php
use Migrations\AbstractMigration;

class AlterPemohonAddUsername extends AbstractMigration
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
        $table = $this->table('pemohon');

        $table->addColumn('username', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => true
        ])->addIndex('username');

        $table->update();
    }
}
