<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

class Test
{
    public ?string $name = null;
    public ?string $city = null;

    /**
     * toArray.
     *
     * @return array
     */
    public function toArray() : array
    {
        return get_object_vars($this);
    }
}
