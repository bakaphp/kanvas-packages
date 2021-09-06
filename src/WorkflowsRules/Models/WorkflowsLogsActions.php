<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

class WorkflowsLogsActions extends BaseModel
{
    public int $workflows_logs_id;
    public int $actions_id;
    public string $action_name;
    public int $status;
    public ?string $result = null;
    public ?string $error = null;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->setSource('workflows_logs_actions');

        $this->belongsTo(
            'workflows_logs_id',
            WorkflowsLogs::class,
            'id',
            [
                'alias' => 'workflows_logs'
            ]
        );

        $this->belongsTo(
            'actions_id',
            RulesWorkflowActions::class,
            'id',
            [
                'alias' => 'actions'
            ]
        );
    }
}
