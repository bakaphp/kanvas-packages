<?php

namespace Kanvas\Packages\WorkflowsRules\Contracts\Interfaces;

interface ActionInterfaces
{
    public function handle(WorkflowsEntityInterfaces $entity, array $params = [], ...$args) : array;

    public function getMessage() : ?string;

    public function getData() : ?array;

    public function getStatus() : int;
}
