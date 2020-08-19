<?php

namespace Kanvas\Packages\Wallets\Models;

/**
 * Class TransactionsItems.
 * transaction within the wallet
 */
class TransactionsItems extends BaseModel
{
    public int $transactions_id;
    public int $amount;
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

        $this->setSource('wallet_transactions_items');
        $this->hasOne('wallet_id', 'Kanvas\\Packages\\Wallets\\Models\\Wallets', 'id', ['alias' => 'wallets']);
    }
}
