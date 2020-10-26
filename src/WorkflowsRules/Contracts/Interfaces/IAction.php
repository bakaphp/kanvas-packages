<?php

namespace Kanvas\Packages\WorkflowsRules\Contracts\Interfaces;

interface IAction
{
    public function handle(array $params = []);
}
