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
        return 'entity-' . get_class($this) . '-' . $this->getId();
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

        $conditions = 'entity_id = :entity_id: AND interactions_id = :interactions_id: AND is_deleted = 0';
        $bind = [
            'entity_id' => $this->getId(),
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
     * Cache the total count.
     *
     * @param int $interaction
     * @param string|null $entityNamespace
     *
     * @return int
     */
    public function getTotalCached(int $interaction, ?string $entityNamespace = null) : int
    {
        $redis = Di::getDefault()->get('redis');
        $key = $this->getInteractionStorageKey() . '-' . $interaction . '-' . $entityNamespace;
        $total = (int) $redis->get($key);

        if ($total === 0) {
            $total = $this->getTotal($interaction, $entityNamespace);
            $redis->set($key, $total);
        }

        return $total;
    }

    /**
     * Increment the total of interaction.
     *
     * @return int
     */
    public function increment(int $interaction, ?string $entityNamespace = null) : int
    {
        $total = $this->getTotal($interaction, $entityNamespace) + 1;
        Di::getDefault()->get('redis')->set($total + 1);
        return $total;
    }

    /**
     * decrees the total of interaction.
     *
     * @return int
     */
    public function decrees(int $interaction, ?string $entityNamespace = null) : int
    {
        $total = $this->getTotal($interaction, $entityNamespace) - 1;
        Di::getDefault()->get('redis')->set($total);
        return $total;
    }
}
