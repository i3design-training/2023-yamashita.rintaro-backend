<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTasksTable extends AbstractMigration
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
    public function change(): void
    {
        $table = $this->table('tasks', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'uuid')
            ->addColumn('user_id', 'uuid', ['null' => false])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addColumn('category_id', 'uuid', ['null' => false])
            ->addForeignKey('category_id', 'categories', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->addColumn('title', 'text', ['null' => false])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('due_date', 'date', ['null' => true])
            ->addColumn('status_id', 'uuid', ['null' => false])
            ->addForeignKey('status_id', 'taskstatus', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->create();

        // インデックスの追加
        $this->table('tasks')
            ->addIndex('user_id', ['name' => 'idx_tasks_user_id'])
            ->addIndex('due_date', ['name' => 'idx_tasks_due_date'])
            ->update();
    }
}
