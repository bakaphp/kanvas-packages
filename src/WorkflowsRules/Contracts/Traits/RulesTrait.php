<?php

declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Contracts\Traits;

use Canvas\Models\Companies;
use Canvas\Models\SystemModules;
use Kanvas\Packages\WorkflowsRules\Jobs\RulesJob;
use Kanvas\Packages\WorkflowsRules\Models\Rules;
use Kanvas\Packages\WorkflowsRules\Models\RulesTypes;
use Phalcon\Di;
use RuntimeException;

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
        $systemModules = SystemModules::getByModelName(get_class($this));

        if ($rulesTypes = RulesTypes::findFirstByName($event)) {
            //Di::getDefault()->get('log')->info("Rules trait started, event {$event}");
            //Di::getDefault()->get('log')->info("Rules trait system module id {$systemModules->getId()}");
            //Di::getDefault()->get('log')->info("Rules trait rules type id  {$rulesTypes->getId()}");
            //Di::getDefault()->get('log')->info("Rules trait company id  {$this->companies->getId()}");

            if (!$this->companies instanceof Companies) {
                throw new RuntimeException('This model doesn\'t have any relationship to a company , cant run rules');
            }

            $rules = Rules::find([
                'conditions' => 'systems_modules_id = :systems_module_id: 
                                AND rules_types_id = :rules_types_id: 
                                AND companies_id in (:companies_id:, :global_companies:)
                ',
                'bind' => [
                    'systems_module_id' => $systemModules->getId(),
                    'rules_types_id' => $rulesTypes->getId(),
                    'companies_id' => $this->companies->getId(),
                    'global_companies' => Companies::GLOBAL_COMPANIES_ID
                ]
            ]);

            foreach ($rules as $rule) {
                RulesJob::dispatch($rule, $event, $this);
            }
        }
    }
}
