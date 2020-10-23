<?php

namespace Kanvas\Packages\WorkflowsRules\Jobs;

use Baka\Jobs\Job;
use Kanvas\Packages\WorkflowsRules\Models\Rules;

class RulesJob extends Job
{
    public Rules $rule;
    public string $event;

    public function __construct(Rules $rules, string $event, object $entity)
    {
        $this->rule = $rules;
        $this->onQueue('workflows');
    }

    public function handle()
    {
    }
}
