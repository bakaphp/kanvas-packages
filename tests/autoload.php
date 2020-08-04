<?php

/**
 * Setup autoloading.
 */

use function Baka\appPath;

use Dotenv\Dotenv;
use Phalcon\Loader;

if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', dirname(__DIR__) . '/');
}

//load classes
$loader = new Loader();
$loader->registerNamespaces([
    'Kanvas\Packages' => appPath('src/'),
    'Kanvas\Packages\Test' => appPath('tests/'),
    'Kanvas\Packages\Test\Support' => appPath('tests/_support'),
    'Baka\Database' => appPath('vendor/baka/baka/src/database'),
]);

$loader->register();

require appPath('vendor/autoload.php');

$dotEnv = Dotenv::createImmutable(__DIR__.'/../');
$dotEnv->load();
