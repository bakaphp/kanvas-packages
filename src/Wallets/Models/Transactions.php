<?php

namespace Kanvas\Packages\Wallets\Models;

use Gewaer\Models\Users;

/**
 * Class Transactions.
 * transaction within the wallet.
 */
class Transactions extends BaseModel
{
    public int $users_id = 0;
    public int $wallet_id = 0;
    public float $subtotal = 0;
    public float $total = 0;
    public float $tax = 0;
    public ?string $concept = null;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('wallet_transactions');
        $this->hasOne('wallet_id', Wallets::class, 'id', ['alias' => 'wallets']);
        $this->hasOne('users_id', Users::class, 'id', ['alias' => 'users']);
        $this->hasMany('id', TransactionsItems::class, 'transactions_id', ['alias' => 'items']);
    }
}
