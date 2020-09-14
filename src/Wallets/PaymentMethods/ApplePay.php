<?php

namespace Kanvas\Packages\Wallets\PaymentMethods;

use Gewaer\Models\Users;
use Kanvas\Packages\MobilePayments\Contracts\ReceiptValidatorTrait;
use Exception;

class ApplePay implements PaymentMethodsInterface
{
    /**
     * Receipt Validator Trait
     */
    use ReceiptValidatorTrait;

    /**
     * Can be charged to user
     * @return bool
     */
    public function canCharge() : bool
    {
        return true;
    }

    /**
     * charge the user
     * @param float $amount
     * @return bool
     */
    public function charge(float $amount) : bool
    {
        return true;
    }

    /**
     * Validate Apple Pay payment via receipt
     *
     * @param string $receipt
     *
     * @return array
     */
    public function validatePayment(string $receipt) : array
    {
        $receipt = $this->validateReceipt($receipt);

        if (gettype($receipt) == "string") {
            throw new Exception($receipt);
        }

        return $this->parseReceiptData($receipt, 'apple');
    }
}
