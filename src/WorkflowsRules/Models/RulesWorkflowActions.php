<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

use Baka\Contracts\Database\HashTableTrait;

class RulesWorkflowActions extends BaseModel
{
    use HashTableTrait;
    public int $system_modules_id;
    public int $actions_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->setSource('rules_workflow_actions');

        $this->hasMany(
            'id',
            RulesActions::class,
            'rules_workflow_actions_id',
            [
                'alias' => 'rulesActions'
            ]
        );

        $this->belongsTo(
            'actions_id',
            Actions::class,
            'id',
            [
                'alias' => 'actions'
            ]
        );
    }
}
