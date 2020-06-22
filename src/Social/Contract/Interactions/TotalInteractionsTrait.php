<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Contract\Interactions;

use Kanvas\Packages\Social\Models\Interactions;
use Phalcon\Di;

trait TotalInteractionsTrait
{
    /**
     * @var int
    */
    public $interaction_type_id;
        
    /**
     * Get the interaction key.
     *
     * @return string
     */
    protected function getInteractionStorageKey(): string
    {
        return get_class($this) . '-' . $this->getId();
    }

    /**
    * Get the total likes of the storm.
    *
    * @return integer
    */
    public function getTotal(int $interaction): int
    {
        $key = $this->getInteractionStorageKey() . '-' . $interaction;

        return (int) Di::getDefault()->get('redis')->get($key);
    }

    /**
     * Increment the total of interaction.
     *
     * @return integer
     */
    public function increment(): int
    {
        return Di::getDefault()->get('redis')->incr($this->getInteractionStorageKey());
    }

    /**
     * Decres the total of interaction.
     *
     * @return integer
     */
    public function decrese(): int
    {
        return Di::getDefault()->get('redis')->decr($this->getInteractionStorageKey());
    }

    /**
     * Get total
     *
     * @return integer
     */
    public function getTotalLiked(): int
    {
        return $this->getTotal(Interactions::LIKE);
    }

    /**
     * Get total
     *
     * @return integer
     */
    public function getTotalComments(): int
    {
        return $this->getTotal(Interactions::COMMENT);
    }

    /**
     * Get total
     *
     * @return integer
     */
    public function getTotalReplies(): int
    {
        return $this->getTotal(Interactions::REPLIED);
    }

    /**
     * Get the total by the Key
     *
     * @param string $key
     * @return integer
     */
    public function getTotalByKey(string $key): int
    {
        return (int) Di::getDefault()->get('redis')->get($key);
    }

    /**
     * Get the total of following of the user
     *
     * @return integer
     */
    public function getTotalFollowing(): int
    {
        return $this->getTotal(Interactions::FOLLOWING);
    }

    /**
     * Get the total of following of the user
     *
     * @return integer
     */
    public function getTotalFollowers(): int
    {
        return $this->getTotal(Interactions::FOLLOWERS);
    }
}
