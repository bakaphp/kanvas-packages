<?php

use Phinx\Db\Adapter\MysqlAdapter;

class ChannelMigrationsUpdate extends Phinx\Migration\AbstractMigration
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
            ->changeColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'entity_id',
            ])
            ->changeColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'is_deleted',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'default' => null,
                'after' => 'created_at',
            ])
            ->save();
    }
}
