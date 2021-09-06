<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

class Attributes extends BaseModel
{
    public int $system_modules_id;
    public int $attributes_data_types_id;
    public string $slug;
    public string $label;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->setSource('attributes');
        $this->hasMany(
            'id',
            RulesConditions::class,
            'attributes_id',
            [
                'alias' => 'rulesConditions'
            ]
        );

        $this->belongsTo(
            'attributes_data_types_id',
            AttributesDataTypes::class,
            'id',
            [
                'alias' => 'dataTypes'
            ]
        );
    }
}
