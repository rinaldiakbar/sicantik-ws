<?php
use Migrations\AbstractMigration;

class AlterPesanAddJudul extends AbstractMigration
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
        $table
            ->addColumn('judul', 'string', [
                'default' => null, // general or announcement
                'limit' => 100,
                'null' => true
            ]);
        $table->update();
    }
}
