<?php
use Migrations\AbstractMigration;

class AlterJenisIzinAddDesc extends AbstractMigration
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
        $table = $this->table('jenis_izin');
        $table->addColumn('short_desc', 'string', [
            'default' => null,
            'limit' => 500,
            'null' => true
        ]);
        $table->addColumn('oss_id', 'biginteger', [
            'default' => null,
            'null' => true
        ])->addIndex('oss_id');
        $table->changeColumn('jenis_izin', 'string', [
            'default' => null,
            'limit' => 500,
            'null' => false
        ]);
        $table->update();
    }
}
