<?php

namespace Kanvas\Packages\WorkflowsRules\Models;

class Companies
{
    /**
     * Get the settings base on the key.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key) : ?string
    {
        return getenv($key);
    }
}
