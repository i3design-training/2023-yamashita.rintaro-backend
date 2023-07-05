<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUser extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */

    // ❌ updated_at timestamp default current_timestamp on update current_timestamp
    // "ON UPDATE CURRENT_TIMESTAMP" はMySQL特有の機能であり、PostgreSQLでは直接的にはサポートされていません
    public function change(): void
    {
        $table = $this->table('users', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'uuid')
            ->addColumn('username', 'text', ['null' => false])
            ->addColumn('email', 'text', ['null' => false])
            ->addColumn('password', 'text', ['null' => false])
            ->addColumn('email_verified', 'boolean', ['null' => false, 'default' => false])
            ->addColumn('created_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addIndex(['username', 'email'], ['unique' => true])
            ->create();
    }
}
