<?php

namespace Kanvas\Packages\WorkflowsRules\Services;

use Kanvas\Packages\WorkflowsRules\Models\Rules as RulesModel;
use Phalcon\Di;

class Rules
{
    public RulesModel $rule;
    protected string $condition;

    /**
     * set.
     *
     * @param  RulesModel $rules
     *
     * @return void
     */
    public static function set(RulesModel $rules)
    {
        return (new static())->assignRule($rules);
    }

    /**
     * validate.
     *
     * @param  bool $entity
     *
     * @return bool
     */
    public function validate(object $entity) : bool
    {
        Di::getDefault()->get('log')->info('Rule validate');
        return true;
    }

    /**
     * assignPattern.
     *
     * @param  string $pattern
     *
     * @return void
     */
    private function assignPattern(string $pattern) : Rules
    {
        $this->condition = $pattern;
        return $this;
    }

    /**
     * assignRule.
     *
     * @param  RulesModel $rules
     *
     * @return void
     */
    private function assignRule(RulesModel $rules) : Rules
    {
        $this->rule = $rules;
        $this->assignPattern($rules->pattern);
        return $this;
    }

    /**
     * setByName.
     *
     * @param  string $name
     *
     * @return void
     */
    public static function setByName(string $name) : self
    {
        $rules = RulesModel::findFirstOrFail([
            'conditions' => 'name = :name:',
            'bind' => ['name' => $name]
        ]);

        self::$rules = $rules;
        return new static();
    }

    /**
     * getStringConditions.
     *
     * @return string
     */
    public function getStringConditions() : string
    {
        $conditions = $this->rules->getRulesConditions();
        foreach ($conditions as $key => $condition) {
        }
    }
}
