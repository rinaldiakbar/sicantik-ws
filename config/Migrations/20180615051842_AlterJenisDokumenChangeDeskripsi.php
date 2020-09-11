<?php
use Migrations\AbstractMigration;

class AlterJenisDokumenChangeDeskripsi extends AbstractMigration
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
        $table = $this->table('jenis_dokumen');
        $table
            ->changeColumn('deskripsi', 'string', [
                'default' => null,
                'limit' => 500,
                'null' => false
            ]);
        $table->update();
    }
}
