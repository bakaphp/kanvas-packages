<?php

namespace Kanvas\Packages\WorkflowsRules\Contracts\Interfaces;

interface ActionInterfaces
{
    public function handle(ModelInterfaces $entity, array $params = []);
}
