<?php

use Phinx\Migration\AbstractMigration;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Cake\Datasource\ConnectionManager;

class CreateTableJenisDokumen extends AbstractMigration
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
        ### BEGIN - Create Table jenis_dokumen ###
        $table = $this->table('jenis_dokumen');

        $table->addColumn('kode', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => false
        ])->addIndex(['kode']);

        $table->addColumn('deskripsi', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false
        ])->addIndex(['deskripsi']);

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

        $table->create();
        ### END - Create Table jenis_dokumen ###


        ### BEGIN - Get all name of dokumen from dokumen_pendukung ###
        $jenisDokumenArr = [
            ['kode' => 'izin', 'deskripsi' => 'Izin', 'dibuat_oleh' => 'system'],
            ['kode' => 'lainnya', 'deskripsi' => 'Dokumen lainnya', 'dibuat_oleh' => 'system'],
        ];

        $dokumenPendukungTable = TableRegistry::get('DokumenPendukung');
        $dokumenPendukung = $dokumenPendukungTable->find()
            ->select(['nama_dokumen'])
            ->distinct(['nama_dokumen']);

        foreach ($dokumenPendukung as $dokumen) {
            $jenisDokumenArr[] = [
                'kode' => Inflector::underscore(Inflector::camelize(strtolower($dokumen->nama_dokumen))),
                'deskripsi' => $dokumen->nama_dokumen,
                'dibuat_oleh' => 'system'
            ];
        }
        ### END - Get all name of dokumen from dokumen_pendukung ###


        ### BEGIN - Insert records to jenis_dokumen
        $jenisDokumenTable = TableRegistry::get('JenisDokumen');

        $jenisDokumenEntities = $jenisDokumenTable->newEntities($jenisDokumenArr);

        $jenisDokumenTable->saveMany($jenisDokumenEntities);
        ### END - Insert records to jenis_dokumen


        ### BEGIN - Alter table dokumen_pendukung to add jenis_dokumen_id ###
        $table = $this->table('dokumen_pendukung');

        $table->addColumn('jenis_dokumen_id', 'biginteger', [
            'default' => null,
            'null' => true
        ])->addIndex(['jenis_dokumen_id']);

        $table->update();
        ### END - Alter table dokumen_pendukung to add jenis_dokumen_id ###


        ### BEGIN - Data migration on dokumen_pendukung table ###
        $conn = ConnectionManager::get('default');

        $sql = "UPDATE dokumen_pendukung
                SET jenis_dokumen_id = (SELECT id FROM jenis_dokumen jd WHERE jd.deskripsi = nama_dokumen LIMIT 1)";

        $conn->begin();
        $stmt = $conn->execute($sql);
        $conn->commit();
        ### END - Data migration on dokumen_pendukung table ###


        ### BEGIN - Alter table persyaratan to add jenis_dokumen_id ###
        $table = $this->table('persyaratan');

        $table->addColumn('jenis_dokumen_id', 'biginteger', [
            'default' => null,
            'null' => true
        ])->addIndex(['jenis_dokumen_id']);

        $table->update();
        ### END - Alter table persyaratan to add jenis_dokumen_id ###


        ### BEGIN - Data migration on persyaratan table ###
        $sql = "UPDATE persyaratan
                SET jenis_dokumen_id = (SELECT id FROM jenis_dokumen jd WHERE jd.deskripsi = keterangan LIMIT 1)";
        $sql2 = "UPDATE persyaratan
                SET jenis_dokumen_id = 2 WHERE jenis_dokumen_id IS NULL";

        $conn->begin();
        $conn->execute($sql);
        $conn->execute($sql2);
        $conn->commit();
        ### END - Data migration on persyaratan table ###


        ### BEGIN - Alter table dokumen_pendukung to prevent null on jenis_dokumen_id field ###
        // Remark this block if you have error when rolling back this migration
        $table = $this->table('dokumen_pendukung');

        $table->changeColumn('jenis_dokumen_id', 'biginteger', [
            'default' => null,
            'null' => false
        ]);

        $table->changeColumn('nama_dokumen', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => true
        ]);

        $table->update();
        ### END - Alter table dokumen_pendukung to prevent null on jenis_dokumen_id field ###


        ### BEGIN - Alter table persyaratan to prevent null on jenis_dokumen_id field ###
        // Remark this block if you have error when rolling back this migration
        $table = $this->table('persyaratan');

        $table->changeColumn('jenis_dokumen_id', 'biginteger', [
            'default' => null,
            'null' => false
        ]);

        $table->update();
        ### END - Alter table persyaratan to prevent null on jenis_dokumen_id field ###
    }
}
