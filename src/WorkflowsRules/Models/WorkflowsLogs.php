<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

class WorkflowsLogs extends BaseModel
{
    public ?int $rules_id = null;
    public ?int $actions_id = null;
    public string $start_at;
    public ?string $end_at = null;
    public int $is_succeed = 0;
    public ?array $data = null;
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
    public static function start($rulesId) : WorkflowsLogs
    {
        $log = new WorkflowsLogs;

        $log->assign([
            'rules_id' => $rulesId,
            'start_at' => date('Y-m-d H:i:s'),
        ]);
        $log->saveOrFail();
        return $log;
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
