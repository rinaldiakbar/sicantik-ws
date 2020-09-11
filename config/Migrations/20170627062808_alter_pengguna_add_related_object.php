<?php

use Phinx\Migration\AbstractMigration;

class AlterPenggunaAddRelatedObject extends AbstractMigration
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
        $table = $this->table('pengguna');

        $table->addColumn('related_object_name', 'string', [
            'default' => null, // name of the model
            'limit' => '100',
            'null' => true
        ]);

        $table->addColumn('related_object_id', 'biginteger', [
            'default' => null,
            'null' => true
        ]);

        $table->update();
    }
}
