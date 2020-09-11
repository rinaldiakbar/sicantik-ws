<?php
use Migrations\AbstractMigration;

class AlterElementAddNoUrut extends AbstractMigration
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
        $table = $this->table('element');
        $table
            ->addColumn('no_urut', 'integer', [
                'default' => 1,
                'null' => false
            ])->addIndex('no_urut');
        $table->update();
    }
}