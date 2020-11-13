<?php

declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Providers;

use function Baka\appPath;
use function Baka\envValue;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class LoggerProvider implements ServiceProviderInterface
{
    /**
     * Registers the logger component.
     *
     * @param DiInterface $container
     */
    public function register(DiInterface $container) : void
    {
        $container->setShared(
            'log',
            function () {
                /** @var string $logName */
                $logName = envValue('LOGGER_DEFAULT_FILENAME', 'api.log');
                /** @var string $logPath */
                $logPath = envValue('LOGGER_DEFAULT_PATH', 'storage/logs');
                $logFile = appPath($logPath) . '/' . $logName . '.log';

                $formatter = new LineFormatter("[%datetime%][%level_name%] %message% %context% \n");

                $logger = new Logger('api-logger');

                $handler = new StreamHandler($logFile, Logger::DEBUG);
                $handler->setFormatter($formatter);

                $logger->pushHandler($handler);

                return $logger;
            }
        );
    }
}
