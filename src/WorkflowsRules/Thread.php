<?php

declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules;

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
     * start.
     *
     * @param  Rules $rule
     *
     * @return self
     */
    public function start(Rules $rule) : self
    {
        $this->logs = new WorkflowsLogs();
        $this->logs->rules_id = (int)$rule->id;
        $this->logs->start_at = date('Y-m-d H:i:s');
        $this->logs->save();
        $this->mountInView();

        return $this;
    }


    /**
     * addAction.
     *
     * @param  Action $action
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
     * @param  string $name
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

        return json_decode($log->result, true);
    }


    /**
     * mountInView.
     *
     * @return void
     */
    public function mountInView() : void
    {
        $view = Di::getDefault()->get('view');
        $view->setVar('logs', $this);
        Di::getDefault()->set('view', $view);
    }
}
