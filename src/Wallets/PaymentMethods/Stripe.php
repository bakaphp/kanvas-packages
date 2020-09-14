<?php

namespace Kanvas\Packages\Wallets\PaymentMethods;

use Gewaer\Models\Users;

class Stripe implements PaymentMethodsInterface
{
    public ?Users $users = null;
    public ?string $customerId = null;

    public function __construct(Users $users)
    {
        $this->users = $users;
        $this->customerId = $users->getDefaultCompany()->get('payment_gateway_customer_id');
    }

    /**
     * Can be charged to user
     * @return bool
     */
    public function canCharge() : bool
    {
        return is_null($this->customerId) ? false : true;
    }

    /**
     * charge the user
     * @param float $amount
     * @return Transactions
     */
    public function charge(float $amount) : bool
    {
        return true;
    }
}
