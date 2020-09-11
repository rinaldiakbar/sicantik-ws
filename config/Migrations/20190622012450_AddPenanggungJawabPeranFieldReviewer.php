<?php
use Migrations\AbstractMigration;

class AddPenanggungJawabPeranFieldReviewer extends AbstractMigration
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
        $table = $this->table('penanggung_jawab_peran');
        $table->addColumn('reviewer', 'boolean', [
            'default' => false,
            'null' => false,
        ]);
        $table->update();
    }
}
