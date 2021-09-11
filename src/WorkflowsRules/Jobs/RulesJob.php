<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Jobs;

use Baka\Jobs\Job;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;
use Kanvas\Packages\WorkflowsRules\Models\Rules;
use Kanvas\Packages\WorkflowsRules\Rules as RulesServices;

class RulesJob extends Job
{
    public Rules $rule;
    public string $event;
    public WorkflowsEntityInterfaces $entity;
    public array $args;

    /**
     * Constructor the job.
     *
     * @param Rules $rules
     * @param string $event
     * @param WorkflowsEntityInterfaces $entity
     * @param mixed ...$args
     */
    public function __construct(Rules $rules, string $event, WorkflowsEntityInterfaces $entity, ...$args)
    {
        //set queue
        $this->onQueue('workflows');

        $this->rule = $rules;
        $this->entity = $entity;
        $this->args = $args;
        $this->event = $event;
    }

    /**
     * handle.
     *
     * @return void
     */
    public function handle()
    {
        $rule = new RulesServices($this->rule);

        //execute the rule
        $rule->execute(
            $this->entity,
            ...$this->args
        );
    }
}
