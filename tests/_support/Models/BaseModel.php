<?php
declare(strict_types=1);

namespace Kanvas\Packages\Test\Support\Models;

use Phalcon\Mvc\Model as PhalconModel;

class BaseModel extends PhalconModel
{
    /**
     * Initialize method for model and specify local db connection.
     */
    public function initialize()
    {
        $this->setConnectionService('dbSocial');
    }
}
