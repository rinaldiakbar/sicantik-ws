<?php
use Migrations\AbstractMigration;

class CreateOssLog extends AbstractMigration
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
        # Create OSS Log #
        $table = $this->table('oss_log');
        $table
            ->addColumn('log', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('nib', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false
            ])
            ->addIndex(['nib'])
            ->addColumn('status', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addIndex(['status'])
            ->create();

        # Alter Data Sinc #
        $table = $this->table('data_sinc');
        $table
            ->addColumn('user_akses', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true
            ])
            ->addColumn('pwd_akses', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true
            ])
            ->addColumn('proses', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true
            ])
            ->addIndex(['proses'])
            ->update();

        # Alter Permohonan Izin #
        $table = $this->table('permohonan_izin');
        $table
            ->addColumn('oss_id', 'biginteger', [
                'default' => null,
                'null'    => true
            ])
            ->addIndex(['oss_id'])
            ->addColumn('kode_oss', 'string', [
                'default' => null,
                'limit'   => 25,
                'null'    => true
            ])
            ->addIndex(['kode_oss'])
            ->update();
    }
}
