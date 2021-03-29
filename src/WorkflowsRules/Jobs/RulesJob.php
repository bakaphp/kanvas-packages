<?php

namespace Kanvas\Packages\WorkflowsRules\Jobs;

use Baka\Jobs\Job;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;
use Kanvas\Packages\WorkflowsRules\Models\Rules;
use Kanvas\Packages\WorkflowsRules\Services\Rules as RulesServices;

class RulesJob extends Job
{
    public Rules $rule;
    public string $event;
    public WorkflowsEntityInterfaces $entity;

    /**
     * __construct.
     *
     * @param  Rules $rules
     * @param  string $event
     * @param  WorkflowsEntityInterfaces $entity
     *
     * @return void
     */
    public function __construct(Rules $rules, string $event, WorkflowsEntityInterfaces $entity)
    {
        $this->rule = $rules;
        $this->onQueue('workflows');
        $this->entity = $entity;
    }

    /**
     * handle.
     *
     * @return void
     */
    public function handle()
    {
        $rule = RulesServices::set($this->rule);
        $rule->validate($this->entity);
    }
}
