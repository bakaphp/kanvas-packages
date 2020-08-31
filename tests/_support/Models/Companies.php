<?php

namespace Kanvas\Packages\Test\Support\Models;

class Companies extends BaseModel
{
    public string $language = "EN";

    public int $id = 1;

    public function getId(): int
    {
        return $this->id;
    }
}
