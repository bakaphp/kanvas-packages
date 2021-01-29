<?php

use Phinx\Db\Adapter\MysqlAdapter;

class AddUuidColumnMessagesMigration extends Phinx\Migration\AbstractMigration
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
            ->addColumn('uuid', 'string', [
                'null' => false,
                'default' => null,
                'limit' => 36,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'after' => 'id',
            ])
            ->save();
    }
}
