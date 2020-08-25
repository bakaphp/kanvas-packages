<?php

namespace Kanvas\Packages\Wallets\Models;
use Gewaer\Models\Users;
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
        $this->hasOne('wallet_id', Wallets::class, ['alias' => 'wallets']);
        $this->hasOne('users_id', Users::class, 'id', ['alias' => 'users']);
        $this->hasMany('id', TransactionsItems::class, 'transactions_id', ['alias' => 'items']);
    }
}
