<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

class RulesWorkflowActions extends BaseModel
{
    public int $system_modules_id;
    public string $action;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('rules_workflow_actions');

        $this->hasMany(
            'id',
            RulesActions::class,
            'rules_workflow_actions_id',
            ['alias' => 'rulesActions']
        );
    }
}
