<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

class RulesConditions extends BaseModel
{
    public int $rules_id;
    public int $attributes_id;
    public int $attributes_operators_id;
    public int $is_custom_attributes;
    public string $value;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->setSource('rules_conditions');

        $this->belongsTo(
            'rules_id',
            Rules::class,
            'id',
            ['alias' => 'rules']
        );

        $this->belongsTo(
            'attributes_id',
            Attributes::class,
            'id',
            ['alias' => 'attributes']
        );

        $this->belongsTo(
            'attributes_operators_id',
            AttributesOperators::class,
            'id',
            ['alias' => 'attributesOperators']
        );
    }
}
