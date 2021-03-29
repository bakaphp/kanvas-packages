<?php

namespace Kanvas\Packages\Test\Support\Models;

use Baka\Database\Model;

class BaseModel extends Model
{
    /**
     * Initialize method for model and specify local db connection.
     */
    public function initialize()
    {
        $this->setConnectionService('dbSocial');
    }
}
