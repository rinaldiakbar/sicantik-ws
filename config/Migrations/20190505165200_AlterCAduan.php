<?php
use Migrations\AbstractMigration;

class AlterCAduan extends AbstractMigration
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
        $table->changeColumn('instansi_id', 'biginteger', [
            'default' => null,
            'null' => true,
        ]);
        $table->changeColumn('kategori', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->update();
    }
}
