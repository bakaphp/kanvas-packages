<?php

use Phinx\Db\Adapter\MysqlAdapter;

class AddIsAsyncRules extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->table('rules', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('is_async', 'boolean', [
                'null' => false,
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'params',
            ])
            ->addIndex(['is_async'], [
                'name' => 'is_async',
                'unique' => false,
            ])
            ->save();
    }
}
