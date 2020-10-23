<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

class RulesTypes extends BaseModel
{
    public string $name;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->setSource('rules_types');

        $this->hasMany(
            'id',
            Rules::class,
            'rules_types_id',
            ['alias' => 'rules']
        );
    }
}
