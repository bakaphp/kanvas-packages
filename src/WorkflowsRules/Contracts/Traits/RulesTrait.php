<?php

namespace Kanvas\Packages\WorkflowsRules\Contracts\Traits;

use Baka\Database\SystemModules;
use Kanvas\Packages\WorkflowsRules\Jobs\RulesJob;
use Kanvas\Packages\WorkflowsRules\Models\Rules;
use Kanvas\Packages\WorkflowsRules\Models\RulesTypes;
use Phalcon\Di;

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
        $systemModules = $this->getSystemModules();
        if ($systemModules) {
            $rulesTypes = RulesTypes::findFirstByName($event);
            if (!$rulesTypes) {
                return;
            }
            Di::getDefault()->get('log')->info("Rules trait started, event {$event}");
            Di::getDefault()->get('log')->info("Rules trait system module id {$systemModules->getId()}");
            Di::getDefault()->get('log')->info("Rules trait rules type id  {$rulesTypes->getId()}");
            Di::getDefault()->get('log')->info("Rules trait company id  {$this->companies->getId()}");

            $rules = Rules::find([
                'conditions' => 'systems_modules_id = :systems_module_id: AND rules_types_id = :rules_types_id: AND (companies_id = :companies_id: OR 0)',
                'bind' => [
                    'systems_module_id' => $systemModules->getId(),
                    'rules_types_id' => $rulesTypes->getId(),
                    'companies_id' => $this->companies->getId()
                ]
            ]);

            foreach ($rules as $rule) {
                RulesJob::dispatch($rule, $event, $this);
                Di::getDefault()->get('log')->info("Rules fire {$rule->name}");
            }
        }
    }

    /**
     * getSystemModules.
     *
     * @return SystemModules|null
     */
    public function getSystemModules() : ?SystemModules
    {
        $di = Di::getDefault();
        $appId = $di->get('app')->getId();

        return SystemModules::findFirst([
            'conditions' => 'model_name = :model_name: AND apps_id = :apps_id:',
            'bind' => [
                'model_name' => get_class($this),
                'apps_id' => $appId
            ]
        ]);
    }
}
