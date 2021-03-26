<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;

class WorkflowsLogs extends BaseModel
{
    public ?int $rules_id = null;
    public ?int $actions_id = null;
    public ?string $entity_id = null;
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
     * Start a workflow.
     *
     * @param mixed $rulesId
     * @param WorkflowsEntityInterfaces $entity
     *
     * @return WorkflowsLogs
     */
    public static function start(Rules $rule, WorkflowsEntityInterfaces $entity) : WorkflowsLogs
    {
        $log = new WorkflowsLogs;

        $log->assign([
            'rules_id' => $rule->getId(),
            'entity_id' => $entity->getId(),
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
    public function end() : void
    {
        $this->end_at = date('Y-m-d H:i:s');
        $this->saveOrFail();
    }
}
