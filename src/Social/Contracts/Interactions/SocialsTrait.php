<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Contracts\Interactions;

use Kanvas\Packages\Social\Models\Interactions;

trait SocialsTrait
{
    use TotalInteractionsTrait;

    /**
     * Get total.
     *
     * @return int
     */
    public function getTotalReactions(?string $entityNamespace = null) : int
    {
        return $this->getTotal(Interactions::REACT, $entityNamespace);
    }

    /**
     * Get total.
     *
     * @return int
     */
    public function getTotalComments(?string $entityNamespace = null) : int
    {
        return $this->getTotal(Interactions::COMMENT, $entityNamespace);
    }

    /**
     * Get total.
     *
     * @return int
     */
    public function getTotalReplies(?string $entityNamespace = null) : int
    {
        return $this->getTotal(Interactions::REPLIED, $entityNamespace);
    }

    /**
     * Get the total of following of the user.
     *
     * @return int
     */
    public function getTotalMessages(?string $entityNamespace = null) : int
    {
        return $this->getTotal(Interactions::MESSAGE, $entityNamespace);
    }
}
