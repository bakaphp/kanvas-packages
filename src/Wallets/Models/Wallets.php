<?php

namespace Kanvas\Packages\Wallets\Models;

class Wallets extends BaseModel
{
    public string $name;
    public int $users_id;
    public ?int $total = 0;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('wallets');
        $this->hasMany('id', 'Gewaer\\Models\\Transactions', 'wallet_id', ['alias' => 'transactions']);
    }
}
