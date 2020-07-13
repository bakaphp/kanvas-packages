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
]);

$loader->register();

require appPath('vendor/autoload.php');

(new Dotenv(__DIR__.'/../'))->load();
// dd((new Dotenv(__DIR__.'/../'))->load());

// $dotenv = Dotenv::createImmutable(appPath());
// $dotenv->load();
