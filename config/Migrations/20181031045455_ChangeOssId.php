<?php
use Migrations\AbstractMigration;

class ChangeOssId extends AbstractMigration
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
            ->changeColumn('oss_id', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->changeColumn('nib_id', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ]);
        $table->update();

        ## Alter Perusahaan ##
        $table = $this->table('perusahaan');
        $table->changeColumn('nib_id', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->update();

        ## Alter Pemohon ##
        $table = $this->table('pemohon');
        $table->changeColumn('nib_id', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->update();
    }
}
