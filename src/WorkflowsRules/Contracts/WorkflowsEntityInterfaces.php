<?php

declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Contracts;

interface WorkflowsEntityInterfaces
{
    public function afterRules() : void;

    /**
     * Set related entities.
     *
     * @param mixed ...$rulesRelatedEntities
     *
     * @return void
     */
    public function setRulesRelatedEntities(...$rulesRelatedEntities) : void;
    public function getRulesRelatedEntities() : array;
}
