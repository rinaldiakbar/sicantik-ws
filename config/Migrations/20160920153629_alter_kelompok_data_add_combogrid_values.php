<?php

use Phinx\Migration\AbstractMigration;

class AlterKelompokDataAddCombogridValues extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('kelompok_data');
        $table
            ->addColumn('combogrid_value_col', 'string', [
                'default' => null,
                'limit' => 25,
                'null' => true
            ])
            ->addColumn('combogrid_fields', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true
            ])
            ->addIndex('jenis_sumber');
        $table->update();
    }
}
