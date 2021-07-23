<?php

declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Contracts\Interfaces;

interface WorkflowsEntityInterfaces
{
    /**
     * Implement the chain responsibility to call other methods that we can attach.
     *
     * @return void
     */
    public function afterRules() : void;
}
