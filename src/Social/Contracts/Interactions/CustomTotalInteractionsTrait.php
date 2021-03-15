<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Contracts\Interactions;

trait CustomTotalInteractionsTrait
{
    use TotalInteractionsTrait {
        getInteractionStorageKey as protected parentGetInteractionStorageKey;
    }

    /**
     * @var int
     */
    public $interaction_type_id;

    /**
     * Get the interaction key.
     *
     * @return string
     */
    protected function getInteractionStorageKey() : string
    {
        if (!is_null($this->interaction_type_id)) {
            return $this->parentGetInteractionStorageKey() . '-' . $this->interaction_type_id;
        }
        return $this->parentGetInteractionStorageKey();
    }
}
