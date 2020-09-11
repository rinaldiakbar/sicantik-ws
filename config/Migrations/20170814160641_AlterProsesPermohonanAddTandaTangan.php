<?php
use Migrations\AbstractMigration;

class AlterProsesPermohonanAddTandaTangan extends AbstractMigration
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
        $table = $this->table('proses_permohonan');

        $table->addColumn('file_tanda_tangan', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true
        ]);

        $table->addColumn('tgl_tanda_tangan', 'datetime', [
            'default' => null,
            'null' => true
        ]);

        $table->update();
    }
}
