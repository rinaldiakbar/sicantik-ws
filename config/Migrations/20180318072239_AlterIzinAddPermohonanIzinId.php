<?php
use Migrations\AbstractMigration;

class AlterIzinAddPermohonanIzinId extends AbstractMigration
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
        try {
            $table = $this->table('izin');
            $table->addColumn('permohonan_izin_id', 'biginteger', [
                'default' => null,
                'null' => true
            ])->addIndex('permohonan_izin_id');
            $table->update();
        } catch (\Exception $ex) {
        }
    }
}
