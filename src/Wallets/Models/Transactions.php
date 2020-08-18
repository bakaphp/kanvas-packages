<?php

namespace Kanvas\Packages\Wallets\Models;

class Transactions extends BaseModel
{
    public int $users_id = 0;
    public int $wallet_id = 0;
    public floatval $subtotal;
    public floatval $total;
    public floatval $tax;
    public string $concept;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('wallet_transactions');
        $this->hasOne('wallet_id', 'Kanvas\\Packages\\Wallets\\Models\\Wallets', 'id', ['alias' => 'wallets']);
        $this->hasOne('users_id', 'Gewaer\\Models\\Users', 'id', ['alias' => 'users']);
        $this->hasMany('id', 'Gewaer\\Models\\TransactionsItems', 'transactions_id', ['alias' => 'items']);
    }
}
