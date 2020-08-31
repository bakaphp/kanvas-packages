<?php

use Phinx\Db\Adapter\MysqlAdapter;

class ChannelsMigration extends Phinx\Migration\AbstractMigration
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
            ->changeColumn('last_message_id', 'integer', [
                'null' => true,
                'default' => null,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'description',
            ])
            ->save();
    }
}
