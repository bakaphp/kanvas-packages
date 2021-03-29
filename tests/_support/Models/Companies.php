<?php

namespace Kanvas\Packages\Test\Support\Models;

use Baka\Contracts\Database\HashTableTrait;

class Companies extends BaseModel
{
    use HashTableTrait;

    public string $language = 'EN';

    public function getId() : int
    {
        return $this->id;
    }
}
