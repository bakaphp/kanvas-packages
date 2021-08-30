<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendation\Contracts;

interface Engine
{
    /**
     * Return a instance of the engine connection.
     *
     * @param Engine $recommendation
     *
     * @return mixed
     */
    public static function connect(Engine $recommendation);
}
