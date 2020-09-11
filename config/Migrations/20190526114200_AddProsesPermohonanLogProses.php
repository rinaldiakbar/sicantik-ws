<?php
use Migrations\AbstractMigration;

class AddProsesPermohonanLogProses extends AbstractMigration
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
        $table->addColumn('start_date', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('end_date', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('diproses_oleh', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->update();
    }
}
