<?php

declare(strict_types=1);

namespace Kanvas\Packages\Payments\Providers;

use function Baka\envValue;
use Canvas\Exception\ServerErrorHttpException;
use PDOException;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use TomorrowIdeas\Plaid\Plaid;

class PlaidProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $container
     */
    public function register(DiInterface $container) : void
    {
        $container->setShared(
            'plaid',
            function () {
                try {
                    $plaid = new Plaid(
                        envValue('PLAID_CLIENT_ID'),
                        envValue('PLAID_CLIENT_SECRET'),
                        envValue('PLAID_PUBLIC_KEY')
                    );
                    $plaid->setEnvironment(
                        envValue('PLAID_ENVIRONMENT')
                    );
                } catch (PDOException $e) {
                    throw new ServerErrorHttpException($e->getMessage());
                }
                return $plaid;
            }
        );
    }
}
