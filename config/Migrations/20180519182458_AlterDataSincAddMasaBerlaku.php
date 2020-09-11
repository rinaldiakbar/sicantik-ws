<?php
use Migrations\AbstractMigration;

class AlterDataSincAddMasaBerlaku extends AbstractMigration
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
        $table->addColumn('masa_berlaku', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
