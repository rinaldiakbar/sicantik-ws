<?php
use Migrations\AbstractMigration;

class AlterPermohonanIzinAddUnitId extends AbstractMigration
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
        $table
            ->addColumn('unit_id', 'biginteger', [
                'default' => null,
                'null' => true
            ])->addIndex('unit_id');
        $table->update();
    }
}
