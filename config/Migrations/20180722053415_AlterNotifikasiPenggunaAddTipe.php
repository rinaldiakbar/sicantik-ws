<?php
use Migrations\AbstractMigration;

class AlterNotifikasiPenggunaAddTipe extends AbstractMigration
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
        $table = $this->table('notifikasi_pengguna');
        $table
            ->addColumn('tipe', 'string', [
                'default' => 'general', // general or announcement
                'limit' => 25,
                'null' => false
            ])
            ->changeColumn('grup_notifikasi', 'string', [
                'default' => null, // general or announcement
                'limit' => 100,
                'null' => true
            ]);
        $table->update();
    }
}
