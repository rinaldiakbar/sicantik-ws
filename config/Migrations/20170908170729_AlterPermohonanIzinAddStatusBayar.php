<?php
use Migrations\AbstractMigration;

class AlterPermohonanIzinAddStatusBayar extends AbstractMigration
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
        $table = $this->table('permohonan_izin');

        $table->addColumn('status_bayar', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => true
        ])->addIndex('status_bayar');

        $table->changeColumn('nilai_retribusi', 'decimal', [
            'default' => null,
            'null' => true
        ]);

        $table->update();
    }
}
