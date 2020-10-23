<?php

use function Baka\appPath;
use Dotenv\Dotenv;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Di\FactoryDefault\Cli as CliDI;
use Phalcon\Loader;
use Swoole\Runtime;

require '/app/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(appPath());
$dotenv->load();

// require_once __DIR__ . '/../library/Core/autoload.php';

//Allow the user to use Swoole Coroutines in our CLI Space
Runtime::enableCoroutine();

go(function () use ($argv) {
    // Using the CLI factory default services container
    $di = new CliDI();
    $loader = new Loader();

    $loader->registerDirs(
        [
            __DIR__ . '/tasks',
        ]
    );

    $loader->register();
    $providers = require appPath('src/WorkflowsRules/Cli/config/provider.php');
    foreach ($providers as $provider) {
        (new $provider())->register($di);
    }
    // Create a console application
    $console = new ConsoleApp();

    $console->setDI($di);

    /**
     * Process the console arguments.
     */
    $arguments = [];

    foreach ($argv as $k => $arg) {
        if ($k === 1) {
            $arguments['task'] = $arg;
        } elseif ($k === 2) {
            $arguments['action'] = $arg;
        } elseif ($k >= 3) {
            $arguments['params'][] = $arg;
        }
    }

    try {
        // Handle incoming arguments
        $console->handle($arguments);
    } catch (\Phalcon\Exception $e) {
        // Do Phalcon related stuff here
        // ..
        fwrite(STDERR, $e->getMessage() . PHP_EOL);
        exit(1);
    } catch (\Throwable $throwable) {
        fwrite(STDERR, $throwable->getMessage() . PHP_EOL);
        exit(1);
    } catch (\Exception $exception) {
        fwrite(STDERR, $exception->getMessage() . PHP_EOL);
        exit(1);
    }
});
