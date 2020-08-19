<?php

namespace Kanvas\Packages\Wallets\Models;
use Phalcon\Mvc\Model\Resultset\Simple;

/**
 * Class Products.
 * Manager the products to buy with the wallet
 */
class Products extends BaseModel
{
    public string $name;
    public ?string $sdk = null;
    public float $price;
    public int $type;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->setSource('products');
    }

    /**
     * Active products by type
     */
    public function getActiveByType(int $type) : ?Simple
    {
        return Products::find([
            'conditions' => 'type = ?1 AND is_deleted = 0',
            'bind' => [
                1 => $type
            ]
        ]);
    }
}
