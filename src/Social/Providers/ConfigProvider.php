<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Providers;

use function Baka\appPath;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Config;

class ConfigProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $container
     */
    public function register(DiInterface $container) : void
    {
        $container->setShared(
            'config',
            function () {

                /**
                 * @todo Find a better way to handle unit test file include
                 */
                $data = require appPath('vendor/canvas/core/src/Core/config.php');

                return new Config($data);
            }
        );
    }
}
