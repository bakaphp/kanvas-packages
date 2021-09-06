<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

use Canvas\Models\Behaviors\Uuid;

class WorkflowsLogs extends BaseModel
{
    public ?int $rules_id = null;
    public string $start_at;
    public ?string $end_at = null;
    public int $did_succeed = 0;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->addBehavior(new Uuid());
        $this->setSource('workflows_logs');

        $this->belongsTo(
            'rules_id',
            Rules::class,
            'id',
            [
                'alias' => 'rules'
            ]
        );
    }
}
