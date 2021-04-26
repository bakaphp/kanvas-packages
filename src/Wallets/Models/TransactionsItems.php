<?php

namespace Kanvas\Packages\Wallets\Models;

/**
 * Class TransactionsItems.
 * transaction within the wallet.
 */
class TransactionsItems extends BaseModel
{
    public int $transactions_id;
    public int $amount;
    public float $subtotal;
    public float $total;
    public float $tax;
    public string $concept;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('wallet_transactions_items');
        $this->hasOne('wallet_id', Wallets::class, 'id', ['alias' => 'wallets']);
    }
}
