<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Contract\Traits;

use Kanvas\Packages\Social\Contract\Traits\InteractionsTrait;

trait FollowersTrait
{
    use InteractionsTrait;

    /**
     * Get the followers of an entity
     *
     * @param string $entityId
     * @return array
     */
    public function followers(string $entityId): array
    {
    }

    /**
     * Get the followings of an entity
     *
     * @param string $entityId
     * @return array
     */
    public function following(string $entityId): array
    {
    }
}
