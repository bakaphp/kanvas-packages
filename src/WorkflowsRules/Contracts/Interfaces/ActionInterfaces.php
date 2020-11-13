<?php

namespace Kanvas\Packages\WorkflowsRules\Contracts\Interfaces;

interface ActionInterfaces
{
    public function handle(ModelInterfaces $entity, array $params = []);

    public function getMessage() : ?string;

    public function getData() : ?array;
}
