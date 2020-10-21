<?php

namespace Kanvas\Packages\WorkflowRules\Jobs;

use Baka\Jobs\Jobs;
use Kanvas\Packages\WorkflowsRules\Models\Rules;

class RulesJobs extends Jobs
{
    public Rules $rule;
    public string $event;

    public function __construct(Rules $rules, string $event, object $entity)
    {
        $this->rule = $rules;
    }

    public function handle()
    {
    }
}
