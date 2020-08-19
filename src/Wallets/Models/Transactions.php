<?php

namespace Kanvas\Packages\Wallets\Models;

/**
 * Class Transactions.
 * transaction within the wallet
 */
class Transactions extends BaseModel
{
    public int $users_id = 0;
    public int $wallet_id = 0;
    public floatval $subtotal = 0;
    public floatval $total = 0;
    public floatval $tax = 0;
    public ?string $concept = null;

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
