<?php

declare(strict_types=1);

namespace Kanvas\Packages\Wallets\Contract;

use Kanvas\Packages\Wallets\Models\Wallets;

trait UsersWalletTrait
{
    /**
     * We get all the user's wallets
     * @return 
     */
    public function getWallets()
    {
        return Wallets::find([
            'conditions' => 'users_id = ?0',
            'bind' => [
                0 => $this->getId()
            ]
        ]);
    }

    /**
     * We get all the user's wallets by name
     * @param string $name
     * @return Wallets
     */
    public function getWalletsByName(string $name) : Wallets
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