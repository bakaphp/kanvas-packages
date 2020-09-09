<?php

$dotEnv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotEnv->load();

return [
    'paths' => [
        'migrations' => 'storage/db/Wallets/migrations',
        'seeds' => 'storage/db/Wallets/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'ut_migrations',
        'default_database' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => getenv('DATA_API_WALLET_MYSQL_HOST'),
            'name' => getenv('DATA_API_WALLET_MYSQL_NAME'),
            'user' => getenv('DATA_API_WALLET_MYSQL_USER'),
            'pass' => getenv('DATA_API_WALLET_MYSQL_PASS'),
            'port' => 3306,
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => getenv('DATA_API_WALLET_MYSQL_HOST'),
            'name' => getenv('DATA_API_WALLET_MYSQL_NAME'),
            'user' => getenv('DATA_API_WALLET_MYSQL_USER'),
            'pass' => getenv('DATA_API_WALLET_MYSQL_PASS'),
            'port' => 3306,
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation',
];
