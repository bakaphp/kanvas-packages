<?php

use Phinx\Db\Adapter\MysqlAdapter;

class ChannelsMessagesMigrations extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->table('channels', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('slug', 'string', [
                'null' => false,
                'limit' => 45,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'after' => 'name',
            ])
            ->changeColumn('description', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'after' => 'slug',
            ])
            ->changeColumn('last_message_id', 'integer', [
                'null' => true,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'description',
            ])
            ->addColumn('entity_namespace', 'string', [
                'null' => false,
                'limit' => 45,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'after' => 'last_message_id',
            ])
            ->addColumn('entity_id', 'string', [
                'null' => false,
                'limit' => 45,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'after' => 'entity_namespace',
            ])
            ->changeColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'entity_id',
            ])
            ->changeColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'created_at',
            ])
            ->save();
    }
}
