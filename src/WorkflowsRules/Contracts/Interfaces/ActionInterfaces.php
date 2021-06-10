<?php

declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Contracts\Interfaces;

interface ActionInterfaces
{
    public function handle(WorkflowsEntityInterfaces $entity, array $params = []) : array;

    public function getMessage() : ?string;

    public function getData() : ?array;

    public function getStatus() : int;
}
