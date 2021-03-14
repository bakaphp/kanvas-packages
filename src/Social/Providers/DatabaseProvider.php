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
            'dbSocial',
            function () {
                $options = [
                    'host' => getenv('DATA_API_SOCIAL_MYSQL_HOST'),
                    'username' => getenv('DATA_API_SOCIAL_MYSQL_USER'),
                    'password' => getenv('DATA_API_SOCIAL_MYSQL_PASS'),
                    'dbname' => getenv('DATA_API_SOCIAL_MYSQL_NAME'),
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
