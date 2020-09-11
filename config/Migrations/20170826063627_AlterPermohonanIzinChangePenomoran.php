<?php
use Migrations\AbstractMigration;

class AlterPermohonanIzinChangePenomoran extends AbstractMigration
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
            ->changeColumn('no_permohonan', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false
            ]);
        $table->update();
    }
}
