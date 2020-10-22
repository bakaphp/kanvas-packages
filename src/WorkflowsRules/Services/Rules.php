<?php

namespace Kanvas\Packages\WorkflowRules\Services;

use Kanvas\Packages\WorkflowRules\Models\Rules as RulesModel;

class Rules
{
    public RulesModel $rule;
    protected string $condition;

    public function set(RulesModel $rules)
    {
        $this->rule = $rules;
        $this->condition = $this->rule->pattern;
    }

    /**
     * setByName.
     *
     * @param  string $name
     *
     * @return void
     */
    public function setByName(string $name) : void
    {
        $rules = RulesModel::findFirstOrFail([
            'conditions' => 'name = :name:',
            'bind' => ['name' => $name]
        ]);

        $this->rule = $rules;
    }

    public function getStringConditions() : string
    {
        $conditions = $this->rules->getRulesConditions();
        foreach ($conditions as $key => $condition) {
        }
    }
}
