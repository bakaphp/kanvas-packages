<?php

declare(strict_types=1);

namespace Kanvas\Packages\Wallets\Contract;

use Kanvas\Packages\Wallets\Models\Products;

trait ProductsWalletTrait
{
    public static function getAll()
    {
        return Products::find();
    }

    public function getActiveProducts()
    {
        return Products::findByStatus(1);
    }
}