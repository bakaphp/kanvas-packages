<?php

namespace Kanvas\Packages\WorkflowsRules\Services;

use Exception;
use Kanvas\Packages\WorkflowsRules\Models\Rules as RulesModel;
use Kanvas\Packages\WorkflowsRules\Models\WorkflowsLogs;
use Phalcon\Di;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

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
        if (!method_exists($entity, 'toArray')) {
            $this->workflowLog->error('to array method not  found', $entity->toArray());
            return false;
        }

        if (!property_exists($entity, 'companies') && !method_exists($entity, 'getCompanies')) {
            $this->workflowLog->error('properties companies not  found', $entity->toArray());
            return false;
        }

        Di::getDefault()->get('log')->info('Rule validate');

        $expression = $this->getStringConditions();
        $values = $this->getArrayValueConditions();
        $values = array_merge($values, $entity->toArray());

        Di::getDefault()->get('log')->info('condition ' . $this->getStringConditions());

        $expressionLanguage = new ExpressionLanguage();
        $result = $expressionLanguage->evaluate(
            $expression,
            $values
        );
        if ($result) {
            $actions = $this->rule->getRulesActions();
            foreach ($actions as $action) {
                $workflowLog = WorkflowsLogs::start($this->rule->id);
                $workFlow = $action->getRulesWorkflowActions();
                $objectAction = new $workFlow->actions->model_name;
                try {
                    $workflowLog->actions_id = $action->id;
                    $params = $this->rule->params ? json_decode($this->rule->params, true) : [];
                    $workflowLog->setLog($objectAction->handle($entity, $params));
                    $workflowLog->end();
                } catch (Exception $e) {
                    $workflowLog->message = $e->getMessage();
                    $workflowLog->end();
                }
            }
        }
        return $result;
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

        return (new static())->assignRule($rules);
    }

    /**
     * getStringConditions.
     *
     * @return string
     */
    public function getStringConditions() : string
    {
        $conditions = $this->rule->getRulesConditions();
        $pattern = $this->condition;

        foreach ($conditions as $key => $conditionModel) {
            $condition = "{$conditionModel->attribute_name}  {$conditionModel->operator}  {$conditionModel->attribute_name}Rule";
            $index = ($key + 1);
            $pattern = str_replace($index, $condition, $pattern);
        }
        $pattern = str_replace('AND', 'and', $pattern);
        $pattern = str_replace('OR', 'or', $pattern);

        return $pattern;
    }

    /**
     * getArrayValueConditions.
     *
     * @return array
     */
    public function getArrayValueConditions() : array
    {
        $conditions = $this->rule->getRulesConditions();
        $values = [];

        foreach ($conditions as $key => $conditionModel) {
            $values["{$conditionModel->attribute_name}Rule"] = $conditionModel->value;
        }
        return $values;
    }
}
