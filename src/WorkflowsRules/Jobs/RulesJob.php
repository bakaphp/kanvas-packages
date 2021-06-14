<?php

declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Jobs;

use Baka\Jobs\Job;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;
use Kanvas\Packages\WorkflowsRules\Models\Rules;
use Kanvas\Packages\WorkflowsRules\Services\Rules as RulesServices;
use Phalcon\Di;

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
     * @param  object $entity
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
        Di::getDefault()->set('userData', $this->entity->getUsers());
        $rule = RulesServices::set($this->rule);
        $rule->validate($this->entity);
    }
}
