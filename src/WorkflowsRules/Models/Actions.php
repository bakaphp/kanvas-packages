<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

class Actions extends BaseModel
{
    public string $name;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->setSource('actions');
    }
}
