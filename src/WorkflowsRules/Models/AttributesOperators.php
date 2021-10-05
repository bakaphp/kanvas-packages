<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

class AttributesOperators extends BaseModel
{
    public int $attributes_data_types_id;
    public string $operator;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->setSource('attributes_operators');

        $this->hasMany(
            'id',
            RulesConditions::class,
            'attributes_operators_id',
            [
                'alias' => 'rulesConditions'
            ]
        );
    }
}
