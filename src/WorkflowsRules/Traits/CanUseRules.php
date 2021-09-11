<?php

namespace Kanvas\Packages\WorkflowsRules\Traits;

use Canvas\Models\Companies;
use Kanvas\Packages\WorkflowsRules\Jobs\RulesJob;
use Kanvas\Packages\WorkflowsRules\Models\Rules;
use Kanvas\Packages\WorkflowsRules\Models\RulesTypes;

trait CanUseRules
{
    protected array $rulesRelatedEntities = [];
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

    /**
     * Set the rules related entities.
     *
     * @param array $rulesRelatedEntities
     *
     * @return void
     */
    public function setRulesRelatedEntities(...$rulesRelatedEntities) : void
    {
        $this->rulesRelatedEntities = $rulesRelatedEntities;
    }

    /**
     * Set needed related entities to execute in each action.
     *
     * @return array<string, ModelInterface>
     */
    public function getRulesRelatedEntities() : array
    {
        return $this->rulesRelatedEntities;
    }
}
