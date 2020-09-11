<?php
use Migrations\AbstractMigration;
use Cake\Datasource\ConnectionManager;

class AlterJabatanRenameTable extends AbstractMigration
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
        $table = $this->table('jabatan');
        $table->rename('master_jabatan');
        $table->update();

        ### BEGIN - Data migration on datatabel table ###
        $conn = ConnectionManager::get('default');
        $sql = "UPDATE datatabel set nama_datatabel = 'master_jabatan' WHERE nama_datatabel = 'jabatan'";
        $conn->begin();
        $conn->execute($sql);
        $conn->commit();
        ### END - Data migration on datatabel table ###
    }
}
