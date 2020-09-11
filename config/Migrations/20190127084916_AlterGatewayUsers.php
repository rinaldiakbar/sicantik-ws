<?php
use Migrations\AbstractMigration;

class AlterGatewayUsers extends AbstractMigration
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
        $table = $this->table('gateway_users');
        $table->changeColumn('username', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->update();
    }
}
