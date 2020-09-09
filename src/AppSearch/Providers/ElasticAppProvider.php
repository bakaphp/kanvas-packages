<?php

declare(strict_types=1);

namespace Kanvas\Packages\AppSearch\Providers;

use function Baka\envValue;
use Elastic\AppSearch\Client\ClientBuilder;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class ElasticAppProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $container
     */
    public function register(DiInterface $container) : void
    {
        $container->setShared(
            'elasticApp',
            function () {
                $apiEndpoint = envValue('ELASTIC_APP_HOST', 'http://localhost:3002/');
                $apiKey = envValue('ELASTIC_APP_KEY', 'private-XXXXXXXXXXXX');
                $clientBuilder = ClientBuilder::create($apiEndpoint, $apiKey);

                return $clientBuilder->build();
            }
        );
    }
}
