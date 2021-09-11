<?php

namespace Kanvas\Packages\WorkflowsRules\Traits;

use Canvas\Models\Companies;
use Kanvas\Packages\WorkflowsRules\Jobs\RulesJob;
use Kanvas\Packages\WorkflowsRules\Models\Rules;
use Kanvas\Packages\WorkflowsRules\Models\RulesTypes;

trait CanUseRules
{
    /**
     * fireRules.
     *
     * search rules for companies and systems_modules
     *
     * @param  mixed $event
     *
     * @return void
     */
    public function fireRules(string $event) : void
    {
        $rulesTypes = RulesTypes::findFirstByName($event);
        if (!$rulesTypes) {
            return;
        }

        $rules = Rules::getByModelAndRuleType($this, $rulesTypes);

        foreach ($rules as $rule) {
            RulesJob::dispatch($rule, $event, $this);
        }
    }
}
