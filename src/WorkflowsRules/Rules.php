<?php

namespace Kanvas\Packages\WorkflowsRules;

use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;
use Kanvas\Packages\WorkflowsRules\Models\Rules as RulesModel;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class Rules
{
    public RulesModel $rule;
    protected string $condition;

    /**
     * Construct.
     *
     * @param RulesModel $rule
     */
    public function __construct(RulesModel $rule)
    {
        $this->rule = $rule;
        $this->assignPattern($rule->pattern);
    }

    /**
     * validate.
     *
     * @param WorkflowsEntityInterfaces $entity
     * @param mixed ...$args
     *
     * @return Thread|null
     */
    public function execute(WorkflowsEntityInterfaces $entity) : ?Thread
    {
        //current process rule expression and value
        list('expression' => $expression, 'values' => $values) = $this->getExpressionCondition();

        $values = array_merge(
            $values,
            $entity->toArray()
        );

        $expressionLanguage = new ExpressionLanguage();

        //validate the expression and values with symfony expression language
        $result = $expressionLanguage->evaluate(
            $expression,
            $values
        );

        $thread = null;
        if ($result) {
            //start a thread to execute all rules actions
            $thread = new Thread($this->rule);
            $thread->start();

            $actions = $this->rule->getRulesActions();


            foreach ($actions as $action) {
                $class = $action->getActionsClass();

                if (class_exists($class) && is_subclass_of($class, Actions::class)) {
                    $currentAction = new $class($this->rule, $thread);
                    $currentAction->handle($entity);

                    $thread->addAction(
                        $currentAction,
                        $action
                    );
                }
            }
        }

        return $thread;
    }

    /**
     * assignPattern.
     *
     * @param string $pattern
     *
     * @return Rules
     */
    private function assignPattern(string $pattern) : Rules
    {
        $this->condition = $pattern;
        return $this;
    }

    /**
     * Get pattern.
     *
     * @return string
     */
    public function getPattern() : string
    {
        return $this->condition;
    }

    /**
     * Get the expression conditional to run the rul.
     *
     * [expression] => created_at > created_at_Variable
     * [value] => Array
     *   (
     *       [created_at_Variable] => 2020-01-01
     *   )
     *
     * @return array
     */
    public function getExpressionCondition() : array
    {
        $conditions = $this->rule->getRulesConditions();
        $pattern = $this->condition;
        $variableExpression = 'Variable';
        $values = [];

        foreach ($conditions as $key => $conditionModel) {
            $condition = trim($conditionModel->attribute_name) . ' ' . trim($conditionModel->operator) . ' ' . trim($conditionModel->attribute_name) . $variableExpression;
            $values[$conditionModel->attribute_name . $variableExpression] = $conditionModel->value;

            $index = ($key + 1);
            $pattern = str_replace($index, $condition, $pattern);
        }
        $pattern = str_replace('AND', 'and', $pattern);
        $pattern = str_replace('OR', 'or', $pattern);

        return [
            'expression' => $pattern,
            'values' => $values
        ];
    }
}
