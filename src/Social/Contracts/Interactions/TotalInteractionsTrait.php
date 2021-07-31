<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Contracts\Interactions;

use Kanvas\Packages\Social\Models\UsersInteractions;
use Phalcon\Di;

trait TotalInteractionsTrait
{
    /**
     * Get the interaction key.
     *
     * @return string
     */
    protected function getInteractionStorageKey() : string
    {
        return get_class($this) . '-' . $this->getId();
    }

    /**
     * Get the total likes of the storm.
     *
     * @return int
     */
    public function getTotal(int $interaction, ?string $entityNamespace = null) : int
    {
        //$redis = Di::getDefault()->get('redis');
        //$key = $this->getInteractionStorageKey() . '-' . $interaction . '-' . $entityNamespace;
        //$total = (int) $redis->get($key);

        $conditions = 'users_id = :users_id: AND interactions_id = :interactions_id: AND is_deleted = 0';
        $bind = [
            'users_id' => $this->getId(),
            'interactions_id' => $interaction,
        ];

        if (!is_null($entityNamespace)) {
            $conditions .= ' AND entity_namespace = :entity_namespace:';
            $bind['entity_namespace'] = $entityNamespace;
        }


        $total = UsersInteractions::count([
            'conditions' => $conditions,
            'bind' => $bind,
        ]);

        //$redis->set($key, $total);


        return $total;
    }

    /**
     * Increment the total of interaction.
     *
     * @return int
     */
    public function increment() : int
    {
        return Di::getDefault()->get('redis')->incr($this->getInteractionStorageKey());
    }

    /**
     * decrees the total of interaction.
     *
     * @return int
     */
    public function decrees() : int
    {
        return Di::getDefault()->get('redis')->decr($this->getInteractionStorageKey());
    }

    /**
     * Get the total by the Key.
     *
     * @param string $key
     *
     * @return int
     */
    public function getTotalByKey(string $key) : int
    {
        return (int) Di::getDefault()->get('redis')->get($key);
    }
}
