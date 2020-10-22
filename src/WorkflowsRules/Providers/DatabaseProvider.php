<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Providers;

use Exception;
use PDO;
use PDOException;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class DatabaseProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $container
     */
    public function register(DiInterface $container) : void
    {
        $container->setShared(
            'dbWorkflow',
            function () {
                $options = [
                    'host' => getenv('WORKFLOW_MYSQL_HOST'),
                    'username' => getenv('WORKFLOW_MYSQL_NAME'),
                    'password' => getenv('WORKFLOW_MYSQL_USER'),
                    'dbname' => getenv('WORKFLOW_MYSQL_PASS'),
                    'charset' => 'utf8',
                    'options' => [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]
                ];

                try {
                    $connection = new Mysql($options);

                    // Set everything to UTF8
                    $connection->execute('SET NAMES utf8mb4', []);
                } catch (PDOException $e) {
                    throw new Exception($e->getMessage());
                }

                return $connection;
            }
        );
    }
}
