<?php

namespace Kanvas\Packages\Test\Support\Models;

class App extends BaseModel
{
    public int $id = 1;

    public function getId(): int
    {
        return $this->id;
    }
}
