<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

class WorkflowsLogs extends BaseModel
{
    public ?int $rules_id = null;
    public ?int $actions_id = null;
    public string $start_at;
    public ?string $end_at = null;
    public int $did_succeed = 1;
    public ?string $data = null;
    public ?string $message = null;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->setSource('workflows_logs');

        $this->belongsTo(
            'rules_id',
            Rules::class,
            'id',
            ['alias' => 'rules']
        );
    }

    /**
     * start.
     *
     * @return WorkflowsLogs
     */
    public static function start(Rules $rule, Actions $action) : WorkflowsLogs
    {
        $log = new WorkflowsLogs;

        $log->setAction($action);
        $log->assign([
            'rules_id' => $rule->getId(),
            'start_at' => date('Y-m-d H:i:s'),
        ]);
        $log->saveOrFail();
        return $log;
    }

    /**
     * setLog.
     *
     * @param  array $log
     *
     * @return void
     */
    public function setLog(array $log) : void
    {
        $this->data = json_encode($log);
        $this->message = key_exists('message', $log) ? $log['message'] : $this->message;
        $this->did_succeed = key_exists('status', $log) ? $log['status'] : $this->did_succeed;
        $this->message = key_exists('message', $log) ? $log['message'] : $this->message;
    }

    /**
     * end.
     *
     * @return void
     */
    public function end(?string $message = null) : void
    {
        $this->message = $message;
        $this->end_at = date('Y-m-d H:i:s');
        $this->saveOrFail();
    }

    /**
     * Set actions.
     *
     * @param Action $action
     *
     * @return void
     */
    public function setAction(Actions $action) : void
    {
        $this->actions_id = $action->getId();
    }

    /**
     * This workload failed.
     *
     * @return void
     */
    public function failed() : void
    {
        $this->did_succeed = 0;
    }
}
