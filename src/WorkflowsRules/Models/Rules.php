<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

class Rules extends BaseModel
{
    public int $systems_modules_id;
    public int $companies_id;
    public int $rules_types_id;
    public string $name;
    public ?string $description = null;
    public string $pattern;
    public ?string $params = null;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->setSource('rules');

        $this->hasMany(
            'id',
            RulesConditions::class,
            'rules_id',
            [
                'alias' => 'rulesConditions'
            ]
        );

        $this->hasMany(
            'id',
            RulesActions::class,
            'rules_id',
            [
                'alias' => 'rulesActions'
            ]
        );

        $this->belongsTo(
            'rules_types_id',
            RulesTypes::class,
            'id',
            [
                'alias' => 'rulesTypes'
            ]
        );
    }
}
