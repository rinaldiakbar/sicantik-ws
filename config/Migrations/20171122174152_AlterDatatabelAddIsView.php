<?php
use Migrations\AbstractMigration;

class AlterDatatabelAddIsView extends AbstractMigration
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
        $table = $this->table('datatabel');
        $table->addColumn('is_view', 'integer', [
            'default' => 0,
            'limit' => 5,
            'null' => false,
        ]);
        $table->update();
    }
}
