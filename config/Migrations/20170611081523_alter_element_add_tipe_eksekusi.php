<?php

use Phinx\Migration\AbstractMigration;

class AlterElementAddTipeEksekusi extends AbstractMigration
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
        $table = $this->table('element');

        $table->addColumn('tipe_eksekusi', 'string', [
            'default' => 'synchronous', // 'asynchronous' or 'synchronous'
            'limit' => '25',
            'null' => true
        ]);

        $table->update();
    }
}
