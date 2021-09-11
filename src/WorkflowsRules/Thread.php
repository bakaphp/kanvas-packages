<?php

declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules;

use function Baka\isJson;
use Kanvas\Packages\WorkflowsRules\Actions\Action;
use Kanvas\Packages\WorkflowsRules\Models\Rules;
use Kanvas\Packages\WorkflowsRules\Models\RulesActions;
use Kanvas\Packages\WorkflowsRules\Models\WorkflowsLogs;
use Kanvas\Packages\WorkflowsRules\Models\WorkflowsLogsActions;
use Phalcon\Di;

class Thread
{
    protected Rules $rule;
    protected WorkflowsLogs $logs;

    /**
     * Constructor.
     *
     * @param Rules $rule
     */
    public function __construct(Rules $rule)
    {
        $this->rule = $rule;
        $this->logs = new WorkflowsLogs();
    }

    /**
     * start.
     *
     *
     * @return self
     */
    public function start() : self
    {
        $this->logs->rules_id = $this->rule->getId();
        $this->logs->start_at = date('Y-m-d H:i:s');
        $this->logs->save();
        $this->mountInView();

        return $this;
    }

    /**
     * Get the thread logs.
     *
     * @return WorkflowsLogs
     */
    public function getLogs() : WorkflowsLogs
    {
        return $this->logs;
    }

    /**
     * addAction.
     *
     * @param Action $action
     *
     * @return self
     */
    public function addAction(Action $action, RulesActions $actionModel) : self
    {
        $actionLog = new WorkflowsLogsActions();
        $actionLog->saveOrFail([
            'workflows_logs_id' => $this->logs->id,
            'actions_id' => $actionModel->rules_workflow_actions_id,
            'action_name' => $actionModel->getActionsName(),
            'status' => $action->getStatus(),
            'result' => json_encode($action->getResults()),
            'error' => $action->getError()
        ]);
        return $this;
    }


    /**
     * getResults.
     *
     * @param string $name
     *
     * @return array
     */
    public function getResults(string $name) : array
    {
        $log = WorkflowsLogsActions::findFirstOrFail([
            'conditions' => 'action_name = :action_name: AND workflows_logs_id = :workflows_logs_id:',
            'bind' => [
                'action_name' => $name,
                'workflows_logs_id' => $this->logs->id
            ]
        ]);

        return !empty($log->result) && isJson($log->result) ? json_decode($log->result, true) : [];
    }


    /**
     * mountInView.
     *
     * @return void
     */
    public function mountInView() : void
    {
        $view = Di::getDefault()->get('view');
        $view->setVar('threads', $this);
        Di::getDefault()->set('view', $view);
    }
}
