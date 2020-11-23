<?php

$dotEnv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotEnv->load();

return [
    'paths' => [
        'migrations' => 'storage/db/migrations',
        'seeds' => 'storage/db/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'ut_migrations',
        'default_database' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => getenv('WORKFLOW_MYSQL_HOST'),
            'name' => getenv('WORKFLOW_MYSQL_HOST'),
            'user' => getenv('WORKFLOW_MYSQL_USER'),
            'pass' => getenv('WORKFLOW_MYSQL_PASS'),
            'port' => 3306,
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => getenv('WORKFLOW_MYSQL_HOST'),
            'name' => getenv('WORKFLOW_MYSQL_HOST'),
            'user' => getenv('WORKFLOW_MYSQL_USER'),
            'pass' => getenv('WORKFLOW_MYSQL_PASS'),
            'port' => 3306,
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation',
];
