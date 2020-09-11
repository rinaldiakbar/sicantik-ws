<?php
use Migrations\AbstractMigration;

class AlterPesanChangePesan extends AbstractMigration
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
            ->changeColumn('pesan', 'text', [
                'default' => null, // general or announcement
                'null' => true
            ]);
        $table->update();
    }
}
