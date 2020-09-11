<?php
use Migrations\AbstractMigration;

class AlterCAduanChangePenyelesaian extends AbstractMigration
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
        $table = $this->table('c_aduan');
        $table->changeColumn('penyelesaian', 'string', [
            'default' => null,
            'limit' => 1000,
            'null' => true,
        ]);
        $table->update();
    }
}
