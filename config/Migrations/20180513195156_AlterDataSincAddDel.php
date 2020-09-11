<?php
use Migrations\AbstractMigration;

class AlterDataSincAddDel extends AbstractMigration
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
        $table = $this->table('data_sinc');
        $table->addColumn('del', 'integer', [
            'default' => 0,
            'limit' => 5,
            'null' => true,
        ]);
        $table->update();

        $table = $this->table('data_sinc_detail');
        $table->addColumn('del', 'integer', [
            'default' => 0,
            'limit' => 5,
            'null' => true,
        ]);
        $table->update();
    }
}
