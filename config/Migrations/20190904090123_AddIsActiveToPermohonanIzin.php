<?php
use Migrations\AbstractMigration;

class AddIsActiveToPermohonanIzin extends AbstractMigration
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
        $table->addColumn('is_active', 'boolean', [
            'default' => true
        ]);
        $table->update();
    }
}
