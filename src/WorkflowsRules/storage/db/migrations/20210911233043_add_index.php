<?php

use Phinx\Db\Adapter\MysqlAdapter;

class AddIndex extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->table('workflows_logs', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->changeColumn('start_at', 'datetime', [
                'null' => false,
                'default' => 'current_timestamp()',
                'after' => 'rules_id',
            ])
            ->changeColumn('end_at', 'datetime', [
                'null' => true,
                'default' => null,
                'after' => 'start_at',
            ])
            ->changeColumn('did_succeed', 'boolean', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'end_at',
            ])
            ->changeColumn('created_at', 'datetime', [
                'null' => false,
                'default' => 'current_timestamp()',
                'after' => 'did_succeed',
            ])
            ->changeColumn('updated_at', 'datetime', [
                'null' => true,
                'default' => null,
                'after' => 'created_at',
            ])
            ->changeColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'updated_at',
            ])
            ->removeColumn('entity_id')
            ->removeIndexByName('entity_id')
            ->save();

        $this->table('rules_conditions', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('is_custom_attributes', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'value',
            ])
            ->removeColumn('is_custom_attriube')
            ->addIndex(['is_custom_attributes'], [
                'name' => 'is_custom_attributes',
                'unique' => false,
            ])
            ->removeIndexByName('attribute_name')
            ->removeIndexByName('operator')
            ->removeIndexByName('is_custom_attriube')
            ->save();

        $this->table('workflows_logs_actions', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->changeColumn('created_at', 'datetime', [
                'null' => false,
                'default' => 'current_timestamp()',
                'after' => 'error',
            ])
            ->save();

        $this->table('rules', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->removeIndexByName('pattern')
            ->save();
    }
}
