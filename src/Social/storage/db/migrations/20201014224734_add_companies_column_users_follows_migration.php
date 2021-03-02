<?php

use Phinx\Db\Adapter\MysqlAdapter;

class AddCompaniesColumnUsersFollowsMigration extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->table('users_follows', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('companies_id', 'int', [
                'null' => true,
                'default' => null,
                'limit' => 11,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'after' => 'entity_id',
            ])
            ->addColumn('companies_branches_id', 'int', [
                'null' => true,
                'default' => null,
                'limit' => 11,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'after' => 'companies_id',
            ])
            ->save();
    }
}
