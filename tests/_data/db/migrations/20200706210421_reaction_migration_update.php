<?php

use Phinx\Db\Adapter\MysqlAdapter;

class ReactionMigrationUpdate extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->execute("ALTER DATABASE COLLATE='utf8mb4_bin';");
        $this->table('reactions', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_bin',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->changeColumn('name', 'string', [
                'null' => false,
                'limit' => 45,
                'collation' => 'utf8mb4_bin',
                'encoding' => 'utf8mb4',
                'after' => 'id',
            ])
            ->changeColumn('icon', 'string', [
                'null' => true,
                'limit' => 45,
                'collation' => 'utf8mb4_bin',
                'encoding' => 'utf8mb4',
                'after' => 'companies_id',
            ])
            ->save();
    }
}
