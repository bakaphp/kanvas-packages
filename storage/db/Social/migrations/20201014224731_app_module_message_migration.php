<?php

use Phinx\Db\Adapter\MysqlAdapter;

class AppModuleMessageMigration extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->table('app_module_message', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('system_modules', 'string', [
                'null' => true,
                'default' => null,
                'limit' => 45,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'after' => 'companies_id',
            ])
            ->changeColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'updated_at',
            ])
            ->removeColumn('system_modules_id')
            ->save();
    }
}
