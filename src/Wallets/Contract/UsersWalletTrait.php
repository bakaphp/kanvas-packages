<?php

declare(strict_types=1);

namespace Kanvas\Packages\Wallets\Contract;

use Kanvas\Packages\Wallets\Models\Wallets;
use Kanvas\Packages\Wallets\PaymentMethods\PaymentMethods;
use Phalcon\Mvc\Model\ResultsetInterface;

/**
 * Methods most used in Users wallet.
 */
trait UsersWalletTrait
{
    public ?PaymentMethods $payment_methods = null;

    /**
     * We get all the user's wallets.
     *
     * @return ResultsetInterface
     */
    public function getWallets() : ResultsetInterface
    {
        return Wallets::find([
            'conditions' => 'users_id = ?0 AND is_deleted = ?1',
            'bind' => [
                0 => $this->getId(),
                1 => 0
            ]
        ]);
    }

    /**
     * We get all the user's wallets by name.
     *
     * @param string $name
     *
     * @return Wallets
     */
    public function getWalletsByName(string $name) : ?Wallets
    {
        return Wallets::findFirst([
            'conditions' => 'users_id = ?0 AND name = ?1',
            'bind' => [
                0 => $this->getId(),
                1 => $name
            ]
        ]);
    }

    /**
     * We get the user's wallets by name.
     *
     * @param string $name
     *
     * @return Wallets
     */
    public function getOrCreateWallet(string $name) : Wallets
    {
        $wallet = $this->getWalletsByName($name);
        if (!$wallet) {
            $wallet = new Wallets();
            $wallet->name = $name;
            $wallet->users_id = $this->getId();
            $wallet->total = 0;
            $wallet->saveOrFail();
        }
        return $wallet;
    }

    /**
     * Set Payment Methods.
     *
     * @param PaymentMethods $paymentMethods
     *
     * @return void
     */
    public function setPaymentMethods(PaymentMethods $paymentMethods) : void
    {
        $this->payment_methods = $paymentMethods;
    }

    /**
     * Get Payment Methods.
     *
     * @param PaymentMethods $paymentMethods
     *
     * @return PaymentMethods
     */
    public function getPaymentMethods() : PaymentMethods
    {
        return $this->payment_methods;
    }
}
