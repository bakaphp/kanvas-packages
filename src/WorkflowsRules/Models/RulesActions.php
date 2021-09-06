<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

class RulesActions extends BaseModel
{
    public int $rules_id;
    public int $rules_workflow_actions_id;
    public string $on;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->setSource('rules_actions');

        $this->belongsTo(
            'rules_id',
            Rules::class,
            'id',
            ['alias' => 'rules']
        );

        $this->belongsTo(
            'rules_workflow_actions_id',
            RulesWorkflowActions::class,
            'id',
            ['alias' => 'rulesWorkflowActions']
        );
    }

    /**
     * getActionsClass.
     *
     * @return string
     */
    public function getActionsClass() : string
    {
        return $this->rulesWorkflowActions->actions->model_name;
    }

    /**
     * getActionsName.
     *
     * @return string
     */
    public function getActionsName() : string
    {
        return $this->rulesWorkflowActions->actions->name;
    }
}
