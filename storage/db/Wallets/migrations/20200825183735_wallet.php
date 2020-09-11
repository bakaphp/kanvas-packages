<?php

use Phinx\Db\Adapter\MysqlAdapter;

class Wallet extends Phinx\Migration\AbstractMigration
{
    public function change()
    {
        $this->table('products', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_bin',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('name', 'string', [
                'null' => true,
                'default' => null,
                'limit' => 100,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('sdk', 'string', [
                'null' => true,
                'default' => null,
                'limit' => 100,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'name',
            ])
            ->addColumn('price', 'decimal', [
                'null' => true,
                'default' => null,
                'precision' => '18',
                'scale' => '2',
                'after' => 'sdk',
            ])
            ->addColumn('type', 'integer', [
                'null' => true,
                'default' => null,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'price',
            ])
            ->addColumn('json', 'text', [
                'null' => true,
                'default' => null,
                'limit' => 65535,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'type',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => true,
                'default' => null,
                'after' => 'json',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'default' => null,
                'after' => 'created_at',
            ])
            ->addColumn('is_deleted', 'integer', [
                'null' => true,
                'default' => null,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'updated_at',
            ])
            ->create();
        
        $this->table('wallet_transactions', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_bin',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('wallet_id', 'integer', [
                'null' => true,
                'default' => null,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'id',
            ])
            ->addColumn('concept', 'string', [
                'null' => true,
                'default' => null,
                'limit' => 255,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'wallet_id',
            ])
            ->addColumn('subtotal', 'decimal', [
                'null' => true,
                'default' => null,
                'precision' => '18',
                'scale' => '2',
                'after' => 'concept',
            ])
            ->addColumn('total', 'decimal', [
                'null' => true,
                'default' => null,
                'precision' => '18',
                'scale' => '2',
                'after' => 'subtotal',
            ])
            ->addColumn('tax', 'decimal', [
                'null' => true,
                'default' => null,
                'precision' => '18',
                'scale' => '2',
                'after' => 'total',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => true,
                'default' => null,
                'after' => 'tax',
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
            ->create();
       
        $this->table('wallets', [
                'id' => false,
                'primary_key' => ['id'],
                'engine' => 'InnoDB',
                'encoding' => 'utf8',
                'collation' => 'utf8_bin',
                'comment' => '',
                'row_format' => 'DYNAMIC',
            ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
            ])
            ->addColumn('name', 'string', [
                'null' => true,
                'default' => null,
                'limit' => 255,
                'collation' => 'utf8_bin',
                'encoding' => 'utf8',
                'after' => 'id',
            ])
            ->addColumn('users_id', 'integer', [
                'null' => true,
                'default' => null,
                'limit' => MysqlAdapter::INT_REGULAR,
                'after' => 'name',
            ])
            ->addColumn('total', 'decimal', [
                'null' => true,
                'default' => null,
                'precision' => '18',
                'scale' => '2',
                'after' => 'users_id',
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => true,
                'default' => null,
                'after' => 'total',
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
                'default' => null,
                'after' => 'created_at',
            ])
            ->addColumn('is_deleted', 'integer', [
                'null' => true,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
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
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'identity' => 'enable',
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
    }
}
