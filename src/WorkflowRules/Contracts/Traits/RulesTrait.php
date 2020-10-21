<?php

namespace Kanvas\Packages\WorkflowsRules\Contracts\Traits;

use Kanvas\Packages\WorkflowsRules\Jobs\RulesJob;
use Kanvas\Packages\WorkflowsRules\Models\Rules;
use Kanvas\Packages\WorkflowsRules\Models\RulesTypes;

trait RulesTrait
{
    public function fireRules(string $event)
    {
        if ($this->system_modules) {
            $rulesTypes = RulesTypes::findFirstByName($event);

            Rules::find([
                'conditions' => 'systems_modules_id = :system_module_id: AND rules_types_id = :rule_type_id:',
                'bind' => [
                    'system_module_id' => $this->system_modules->getId(),
                    'rule_type_id' => $rulesTypes->getId()
                ]
            ]);

            foreach ($rules as $rule) {
                RulesJob::dispatch($rule, $event);
            }
        }
    }
}
