<?php

namespace Kanvas\Packages\Wallets\PaymentMethods;

interface PaymentMethodsInterface
{
    /**
     * Can be charged to user
     * @return bool
     */
    public function canCharge() : bool;

    /**
     * charge the user
     */
    public function charge(float $amount) : bool;
}