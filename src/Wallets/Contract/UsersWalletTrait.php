<?php

declare(strict_types=1);

namespace Kanvas\Packages\Wallets\Contract;

use Kanvas\Packages\Wallets\Models\Wallets;
use Phalcon\Mvc\Model\Resultset\Simple;

/**
 * Methods most used in Users wallet
 */
trait UsersWalletTrait
{
    /**
     * We get all the user's wallets
     * @return Simple
     */
    public function getWallets() : ?Simple
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
     * We get all the user's wallets by name
     * @param string $name
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
     * We get the user's wallets by name
     * @param string $name
     * @return Wallets
     */
    public function getOrCreateWallet(string $name) : Wallets
    {
        $wallet = $this->getWalletsByName($name);
        if(!$wallet){
            $wallet = new Wallets();
            $wallet->name = $name;
            $wallet->users_id = $this->getId();
            $wallet->total = 0;
            $wallet->saveOrFail();
        }
        return $wallet;
    }
}