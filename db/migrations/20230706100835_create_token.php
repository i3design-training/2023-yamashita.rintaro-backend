<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateToken extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('tokens', ['id' => false, 'primary_key' => ['id']]);

        $table->addColumn('id', 'uuid')
            ->addColumn('user_id', 'uuid')
            ->addColumn('token', 'text', ['null' => false])
            ->addColumn('expiry_date', 'timestamp', ['timezone' => true, 'null' => false])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->create();

        $table->addIndex(['user_id'], ['name' => 'idx_tokens_user_id'])
            ->update();
    }
}
