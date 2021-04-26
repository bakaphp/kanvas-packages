<?php

namespace Kanvas\Packages\Test\Support\Helper;

use Baka\TestCase\Phinx as TestCasePhinx;

class Phinx extends TestCasePhinx
{
    /**
     * Run migration.
     *
     * @return string
     */
    public static function migrate() : string
    {
        return self::getPhinx('tests/phinx.php')->getMigrate();
    }

    /**
     * Run seed.
     *
     * @return void
     */
    public static function seed()
    {
        return self::getPhinx('tests/phinx.php')->getSeed();
    }
}
