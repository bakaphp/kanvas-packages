<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

use Baka\Contracts\Database\ModelInterface;
use function Baka\isJson;
use Canvas\Models\Companies;
use Canvas\Models\SystemModules;

use Phalcon\Mvc\Model\ResultsetInterface;

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

    /**
     * Get rule for the given model and rule type.
     *
     * @param ModelInterface $model
     * @param string $ruleType
     *
     * @return ResultsetInterface
     */
    public static function getByModelAndRuleType(ModelInterface $model, RulesTypes $rulesTypes) : ResultsetInterface
    {
        $systemModules = SystemModules::getByModelName(get_class($model));

        $bind = [
            'systems_module_id' => $systemModules->getId(),
            'rules_types_id' => $rulesTypes->getId(),
            'companies_id' => Companies::GLOBAL_COMPANIES_ID,
            'global_companies' => Companies::GLOBAL_COMPANIES_ID
        ];

        //if it has a company reference
        if (isset($model->companies) && is_object($model->companies)) {
            $bind['companies_id'] = $model->companies->getId();
        }

        return  Rules::find([
            'conditions' => 'systems_modules_id = :systems_module_id: 
                                AND rules_types_id = :rules_types_id: 
                                AND companies_id in (:companies_id:, :global_companies:)',
            'bind' => $bind
        ]);
    }

    /**
     * Get current rule params.
     *
     * @return array
     */
    public function getParams() : array
    {
        return !empty($this->params) && isJson($this->params) ? json_decode($this->params, true) : [];
    }
}
