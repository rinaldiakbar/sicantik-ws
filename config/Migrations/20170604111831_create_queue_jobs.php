<?php

use Phinx\Migration\AbstractMigration;

class CreateQueueJobs extends AbstractMigration
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
        $table = $this->table('queue_jobs');

        $table->addColumn('type', 'string', [
            'default' => null,
            'limit' => 30,
            'null' => false
        ])->addIndex(['type']);

        $table->addColumn('body', 'text', [
            'default' => null,
            'null' => false
        ]);

        $table->addColumn('status', 'string', [
            'default' => 'WAITING', // WAITING, IN QUEUE, PROCESSING, FAILED, FINISHED
            'limit' => 15,
            'null' => false
        ])->addIndex(['status']);

        $table->addColumn('priority', 'integer', [
            'default' => 10,
            'null' => false
        ])->addIndex('priority');

        $table->addColumn('delay_time', 'integer', [
            'default' => 0,
            'null' => false
        ]);

        $table->addColumn('execution_time', 'datetime', [
            'default' => null,
            'limit' => 20,
            'null' => false
        ]);

        $table->addColumn('dibuat_oleh', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => true
        ]);

        $table->addColumn('tgl_dibuat', 'datetime', [
            'default' => null,
            'limit' => 20,
            'null' => true
        ]);

        $table->addColumn('tgl_diubah', 'datetime', [
            'default' => null,
            'limit' => 20,
            'null' => true
        ]);

        $table->addColumn('diubah_oleh', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => true
        ]);

        $table->addColumn('instansi_id', 'biginteger', [
            'default' => null,
            'null' => true
        ])->addIndex('instansi_id');

        $table->create();
    }
}
