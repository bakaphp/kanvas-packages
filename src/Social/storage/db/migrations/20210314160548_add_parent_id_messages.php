<?php

use Phinx\Db\Adapter\MysqlAdapter;

class AddParentIdMessages extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->table('messages', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('parent_id', 'integer', [
                'null' => true,
                'default' => 0,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'entity_id',
            ])
            ->addColumn('parent_unique_id', 'char', [
                'null' => true,
                'default' => null,
                'limit' => 64,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'after' => 'id',
            ])
            ->save();
    }
}
