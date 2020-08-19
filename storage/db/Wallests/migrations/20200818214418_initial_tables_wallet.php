<?php

use Phinx\Db\Adapter\MysqlAdapter;

class InitialTablesWallet extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->table('wallets', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
        ->addColumn('name', 'string', [
            'null' => false,
            'limit' => 100,
            'collation' => 'utf8mb4_general_ci',
            'encoding' => 'utf8mb4',
            'after' => 'id',
        ])
        ->addColumn('users_id', 'integer', [
            'null' => false,
            'limit' => MysqlAdapter::INT_REGULAR,
            'after' => 'name',
        ])
        ->addColumn('total', 'decimal', [
            'null' => true,
            'precision' => '18',
            'scale' => '2',
            'after' => 'users_id',
        ])
        ->addColumn('created_at', 'datetime', [
            'null' => false,
            'after' => 'total',
        ])
        ->addColumn('updated_at', 'datetime', [
            'null' => true,
            'after' => 'created_at',
        ])
        ->addColumn('is_deleted', 'integer', [
            'null' => true,
            'default' => '0',
            'limit' => MysqlAdapter::INT_TINY,
            'after' => 'updated_at',
        ])
        ->create();

        $this->table('wallet_transactions', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
        ->addColumn('wallet_id', 'integer', [
            'null' => false,
            'limit' => MysqlAdapter::INT_REGULAR,
            'after' => 'id',
        ])
        ->addColumn('concept', 'string', [
            'null' => false,
            'limit' => 255,
            'collation' => 'utf8mb4_general_ci',
            'encoding' => 'utf8mb4',
            'after' => 'wallet_id',
        ])
        ->addColumn('subtotal', 'decimal', [
            'null' => true,
            'precision' => '18',
            'scale' => '2',
            'after' => 'concept',
        ])
        ->addColumn('total', 'decimal', [
            'null' => true,
            'precision' => '18',
            'scale' => '2',
            'after' => 'subtotal',
        ])
        ->addColumn('tax', 'decimal', [
            'null' => true,
            'precision' => '18',
            'scale' => '2',
            'after' => 'total',
        ])
        ->addColumn('created_at', 'datetime', [
            'null' => false,
            'after' => 'tax',
        ])
        ->addColumn('updated_at', 'datetime', [
            'null' => true,
            'after' => 'created_at',
        ])
        ->addColumn('is_deleted', 'integer', [
            'null' => true,
            'default' => '0',
            'limit' => MysqlAdapter::INT_TINY,
            'after' => 'updated_at',
        ])
        ->create();

        $this->table('wallet_transactions_items', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
        ->addColumn('transactions_id', 'integer', [
            'null' => false,
            'limit' => MysqlAdapter::INT_REGULAR,
            'after' => 'id',
        ])
        ->addColumn('concept', 'string', [
            'null' => false,
            'limit' => 255,
            'collation' => 'utf8mb4_general_ci',
            'encoding' => 'utf8mb4',
            'after' => 'transactions_id',
        ])
        ->addColumn('amount', 'decimal', [
            'null' => true,
            'precision' => '18',
            'scale' => '2',
            'after' => 'concept',
        ])
        ->addColumn('subtotal', 'decimal', [
            'null' => true,
            'precision' => '18',
            'scale' => '2',
            'after' => 'amount',
        ])
        ->addColumn('total', 'decimal', [
            'null' => true,
            'precision' => '18',
            'scale' => '2',
            'after' => 'subtotal',
        ])
        ->addColumn('tax', 'decimal', [
            'null' => true,
            'precision' => '18',
            'scale' => '2',
            'after' => 'total',
        ])
        ->addColumn('created_at', 'datetime', [
            'null' => false,
            'after' => 'tax',
        ])
        ->addColumn('updated_at', 'datetime', [
            'null' => true,
            'after' => 'created_at',
        ])
        ->addColumn('is_deleted', 'integer', [
            'null' => true,
            'default' => '0',
            'limit' => MysqlAdapter::INT_TINY,
            'after' => 'updated_at',
        ])
        ->create();

        $this->table('products', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
        ->addColumn('name', 'string', [
            'null' => false,
            'limit' => 100,
            'collation' => 'utf8mb4_general_ci',
            'encoding' => 'utf8mb4',
            'after' => 'id',
        ])
        ->addColumn('sdk', 'string', [
            'null' => true,
            'limit' => 100,
            'collation' => 'utf8mb4_general_ci',
            'encoding' => 'utf8mb4',
            'after' => 'name',
        ])
        ->addColumn('price', 'decimal', [
            'null' => true,
            'precision' => '18',
            'scale' => '2',
            'after' => 'sdk',
        ])
        ->addColumn('type', 'integer', [
            'null' => true,
            'default' => '0',
            'limit' => MysqlAdapter::INT_TINY,
            'after' => 'price',
        ])
        ->addColumn('created_at', 'datetime', [
            'null' => false,
            'after' => 'type',
        ])
        ->addColumn('updated_at', 'datetime', [
            'null' => true,
            'after' => 'created_at',
        ])
        ->addColumn('is_deleted', 'integer', [
            'null' => true,
            'default' => '0',
            'limit' => MysqlAdapter::INT_TINY,
            'after' => 'updated_at',
        ])
        ->create();
    }
}
