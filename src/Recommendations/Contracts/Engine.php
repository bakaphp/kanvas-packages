<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendation\Contracts;

interface Engine
{
    /**
     * Return a instance of the engine connection.
     *
     * @param Engine $database
     *
     * @return mixed
     */
    public static function connect(Database $database);
    public function interactions() : Interactions;
    public function items() : Items;
    public function recommendation() : Recommendation;
}
