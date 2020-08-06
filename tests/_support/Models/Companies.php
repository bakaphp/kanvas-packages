<?php

namespace Kanvas\Packages\Test\Support\Models;

class Companies extends BaseModel
{
    public string $language = "EN";

    public function getId(): int
    {
        return 1;
    }
}
