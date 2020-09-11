<?php
use Migrations\AbstractMigration;

class AlterFaqAddSearch extends AbstractMigration
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
        $table = $this->table('faq');
        $table
            ->addColumn('search', 'string', [
                'default' => null,
                'limit' => 500,
                'null' => true,
            ])
            ->addIndex('search');
        $table->update();
    }
}
