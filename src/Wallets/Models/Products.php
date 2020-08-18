<?php

namespace Kanvas\Packages\Wallets\Models;

class Products extends BaseModel
{
    public string $name;
    public string $sdk;
    public float $price;
    public ?int $status = 0;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
    }
}
