<?php
declare(strict_types=1);

namespace Kanvas\Packages\Social\Providers;

use function Baka\envValue;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Redis;

class RedisProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $container
     */
    public function register(DiInterface $container) : void
    {
        if (!$container->has('events')) {
            $app = envValue('GEWAER_APP_ID', 1);

            $container->setShared(
                'redis',
                function (bool $prefix = true) use ($app) {
                    //Connect to redis
                    $redis = new Redis();
                    $redis->connect(envValue('REDIS_HOST', '127.0.0.1'), (int) envValue('REDIS_PORT', 6379));
                    if ($prefix) {
                        $redis->setOption(Redis::OPT_PREFIX, $app . ':'); // use custom prefix on all keys
                    }
                    $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
                    return $redis;
                }
            );
        }
    }
}
