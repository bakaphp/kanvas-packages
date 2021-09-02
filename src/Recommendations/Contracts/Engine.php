<?php

namespace Kanvas\Packages\Recommendation\Contracts;

interface Engine
{
    public static function connect(Recomendation $recomendation);
}
