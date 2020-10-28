<?php

namespace Kanvas\Packages\WorkflowsRules\Contracts\Interfaces;

interface IAction
{
    public function handle(object $entity, array $params = []);
}
