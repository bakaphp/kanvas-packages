<?php

namespace Kanvas\Packages\Social\Models;

use Canvas\Models\SystemModules as KanvasSystemModules;
use Phalcon\Mvc\ModelInterface;
use Baka\Http\Exception\InternalServerErrorException;
use Phalcon\Di;

class SystemModules extends KanvasSystemModules
{
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
    }
}
