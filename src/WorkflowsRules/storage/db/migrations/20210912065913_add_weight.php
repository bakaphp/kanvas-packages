<?php

use Phinx\Db\Adapter\MysqlAdapter;

class AddWeight extends Phinx\Migration\AbstractMigration
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
            ->changeColumn('uuid', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'rules_id',
            ])
            ->changeColumn('entity_id', 'char', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'uuid',
            ])
            ->changeColumn('start_at', 'datetime', [
                'null' => false,
                'after' => 'entity_id',
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
            ->save();
        $this->table('rules_actions', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_general_ci',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('weight', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => '3',
                'scale' => '2',
                'after' => 'rules_workflow_actions_id',
            ])
            ->changeColumn('created_at', 'datetime', [
                'null' => false,
                'default' => 'current_timestamp()',
                'after' => 'weight',
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
            ->addIndex(['weight'], [
                'name' => 'weight',
                'unique' => false,
            ])
            ->save();
    }
}
