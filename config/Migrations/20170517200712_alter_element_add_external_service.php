<?php

use Phinx\Migration\AbstractMigration;

class AlterElementAddExternalService extends AbstractMigration
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
        
        $table->addColumn('target_simpan', 'string', [
            'default' => null, // internal or eksternal
            'limit' => '10',
            'null' => true
        ]);

        $table->addColumn('service_eksternal_id', 'biginteger', [
            'default' => null,
            'null' => true
        ])->addIndex('service_eksternal_id');

        $table->addColumn('target_uri', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => true
        ]);
        
        $table->update();
    }
}
