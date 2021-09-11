<?php

use Phinx\Db\Adapter\MysqlAdapter;

class UpdateIndex extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->execute("ALTER DATABASE CHARACTER SET 'utf8mb4';");
        $this->execute("ALTER DATABASE COLLATE='utf8mb4_unicode_520_nopad_ci';");
        $this->table('workflows_logs', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('uuid', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('rules_id', 'integer', [
                'null' => true,
                'default' => null,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'uuid',
            ])
            ->addColumn('entity_id', 'char', [
                'null' => false,
                'default' => '',
                'limit' => 50,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'rules_id',
            ])
            ->addColumn('start_at', 'datetime', [
                'null' => false,
                'default' => 'current_timestamp()',
                'after' => 'entity_id',
            ])
            ->addColumn('end_at', 'datetime', [
                'null' => true,
                'default' => null,
                'after' => 'start_at',
            ])
            ->addColumn('did_succeed', 'boolean', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'end_at',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'default' => 'current_timestamp()',
                'after' => 'did_succeed',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'default' => null,
                'after' => 'created_at',
            ])
            ->addColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'updated_at',
            ])
            ->addIndex(['rules_id'], [
                'name' => 'rules_id',
                'unique' => false,
            ])
            ->addIndex(['entity_id'], [
                'name' => 'entity_id',
                'unique' => false,
            ])
            ->addIndex(['start_at'], [
                'name' => 'start_at',
                'unique' => false,
            ])
            ->addIndex(['end_at'], [
                'name' => 'end_at',
                'unique' => false,
            ])
            ->addIndex(['did_succeed'], [
                'name' => 'did_succeed',
                'unique' => false,
            ])
            ->addIndex(['created_at'], [
                'name' => 'created_at',
                'unique' => false,
            ])
            ->addIndex(['uuid'], [
                'name' => 'uuid',
                'unique' => false,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
                'unique' => false,
            ])
            ->create();
        $this->table('actions', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('model_name', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'name',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'default' => 'current_timestamp()',
                'after' => 'model_name',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'default' => null,
                'after' => 'created_at',
            ])
            ->addColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'updated_at',
            ])
            ->addIndex(['model_name'], [
                'name' => 'model_name',
                'unique' => false,
            ])
            ->addIndex(['created_at'], [
                'name' => 'created_at',
                'unique' => false,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
                'unique' => false,
            ])
            ->create();
        $this->table('rules_conditions', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('rules_id', 'integer', [
                'null' => true,
                'default' => null,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('attribute_name', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'rules_id',
            ])
            ->addColumn('operator', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'attribute_name',
            ])
            ->addColumn('value', 'text', [
                'null' => false,
                'limit' => 65535,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'operator',
            ])
            ->addColumn('is_custom_attriube', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'value',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'default' => 'current_timestamp()',
                'after' => 'is_custom_attriube',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'default' => null,
                'after' => 'created_at',
            ])
            ->addColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'updated_at',
            ])
            ->addIndex(['rules_id'], [
                'name' => 'rules_id',
                'unique' => false,
            ])
            ->addIndex(['attribute_name'], [
                'name' => 'attribute_name',
                'unique' => false,
            ])
            ->addIndex(['operator'], [
                'name' => 'operator',
                'unique' => false,
            ])
            ->addIndex(['created_at'], [
                'name' => 'created_at',
                'unique' => false,
            ])
            ->addIndex(['is_custom_attriube'], [
                'name' => 'is_custom_attriube',
                'unique' => false,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
                'unique' => false,
            ])
            ->create();
        $this->table('workflows_logs_actions', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('workflows_logs_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('actions_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'workflows_logs_id',
            ])
            ->addColumn('status', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'actions_id',
            ])
            ->addColumn('action_name', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'status',
            ])
            ->addColumn('result', 'text', [
                'null' => true,
                'default' => null,
                'limit' => 65535,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'action_name',
            ])
            ->addColumn('error', 'text', [
                'null' => true,
                'default' => null,
                'limit' => 65535,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'result',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'after' => 'error',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'default' => null,
                'after' => 'created_at',
            ])
            ->addColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'after' => 'updated_at',
            ])
            ->addIndex(['workflows_logs_id'], [
                'name' => 'workflows_logs_id',
                'unique' => false,
            ])
            ->addIndex(['actions_id'], [
                'name' => 'actions_id',
                'unique' => false,
            ])
            ->addIndex(['status'], [
                'name' => 'status',
                'unique' => false,
            ])
            ->addIndex(['created_at'], [
                'name' => 'created_at',
                'unique' => false,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
                'unique' => false,
            ])
            ->create();
        $this->table('rules_workflow_actions', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('actions_id', 'integer', [
                'null' => true,
                'default' => null,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('system_modules_id', 'integer', [
                'null' => true,
                'default' => null,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'actions_id',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'default' => 'current_timestamp()',
                'after' => 'system_modules_id',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'default' => null,
                'after' => 'created_at',
            ])
            ->addColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'updated_at',
            ])
            ->addIndex(['actions_id'], [
                'name' => 'actions_id',
                'unique' => false,
            ])
            ->addIndex(['system_modules_id'], [
                'name' => 'system_modules_id',
                'unique' => false,
            ])
            ->addIndex(['created_at'], [
                'name' => 'created_at',
                'unique' => false,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
                'unique' => false,
            ])
            ->create();
        $this->table('rules_actions', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('rules_id', 'integer', [
                'null' => true,
                'default' => null,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('rules_workflow_actions_id', 'integer', [
                'null' => true,
                'default' => null,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'rules_id',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'default' => 'current_timestamp()',
                'after' => 'rules_workflow_actions_id',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'default' => null,
                'after' => 'created_at',
            ])
            ->addColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'updated_at',
            ])
            ->addIndex(['rules_id'], [
                'name' => 'rules_id',
                'unique' => false,
            ])
            ->addIndex(['rules_workflow_actions_id'], [
                'name' => 'rules_workflow_actions_id',
                'unique' => false,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
                'unique' => false,
            ])
            ->addIndex(['created_at'], [
                'name' => 'created_at',
                'unique' => false,
            ])
            ->create();
        $this->table('rules_types', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'default' => 'current_timestamp()',
                'after' => 'name',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'default' => null,
                'after' => 'created_at',
            ])
            ->addColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'updated_at',
            ])
            ->addIndex(['created_at'], [
                'name' => 'created_at',
                'unique' => false,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
                'unique' => false,
            ])
            ->create();
        $this->table('rules', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('systems_modules_id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('companies_id', 'integer', [
                'null' => true,
                'default' => null,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'systems_modules_id',
            ])
            ->addColumn('rules_types_id', 'integer', [
                'null' => true,
                'default' => null,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'companies_id',
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'rules_types_id',
            ])
            ->addColumn('description', 'string', [
                'null' => true,
                'default' => null,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'name',
            ])
            ->addColumn('pattern', 'string', [
                'null' => false,
                'limit' => 255,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'description',
            ])
            ->addColumn('params', 'text', [
                'null' => true,
                'default' => null,
                'limit' => MysqlAdapter::TEXT_LONG,
                'collation' => 'utf8_general_ci',
                'encoding' => 'utf8',
                'after' => 'pattern',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => false,
                'default' => 'current_timestamp()',
                'after' => 'params',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'default' => null,
                'after' => 'created_at',
            ])
            ->addColumn('is_deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'updated_at',
            ])
            ->addIndex(['rules_types_id'], [
                'name' => 'rules_types_id',
                'unique' => false,
            ])
            ->addIndex(['systems_modules_id'], [
                'name' => 'systems_modules_id',
                'unique' => false,
            ])
            ->addIndex(['companies_id'], [
                'name' => 'companies_id',
                'unique' => false,
            ])
            ->addIndex(['created_at'], [
                'name' => 'created_at',
                'unique' => false,
            ])
            ->addIndex(['is_deleted'], [
                'name' => 'is_deleted',
                'unique' => false,
            ])
            ->addIndex(['pattern'], [
                'name' => 'pattern',
                'unique' => false,
            ])
            ->create();
    }
}
