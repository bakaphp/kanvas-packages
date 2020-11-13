<?php

namespace Kanvas\Packages\WorkflowsRules\Contracts\Traits;

use Kanvas\Packages\WorkflowsRules\Jobs\RulesJob;
use Kanvas\Packages\WorkflowsRules\Models\Rules;
use Kanvas\Packages\WorkflowsRules\Models\RulesTypes;

trait RulesTrait
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
        if ($this->systems_modules) {
            $rulesTypes = RulesTypes::findFirstByName($event);

            $rules = Rules::find([
                'conditions' => 'systems_modules_id = :systems_module_id: AND rules_types_id = :rules_types_id: AND companies_id = :companies_id:',
                'bind' => [
                    'systems_module_id' => $this->systems_modules->getId(),
                    'rules_types_id' => $rulesTypes->getId(),
                    'companies_id' => $this->companies->getId()
                ]
            ]);

            foreach ($rules as $rule) {
                RulesJob::dispatch($rule, $event, $this);
            }
        }
    }
}
