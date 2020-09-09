<?php

namespace Kanvas\Packages\Test\Support\Models;

use Kanvas\Packages\Social\Contract\Interactions\CustomTotalInteractionsTrait;

class Tag extends BaseModel
{
    use CustomTotalInteractionsTrait;

    public int $id = 1;

    public function getId(): int
    {
        return $this->id;
    }
}
