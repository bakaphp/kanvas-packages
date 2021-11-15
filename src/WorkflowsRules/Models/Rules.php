<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

use Baka\Contracts\Database\ModelInterface;
use function Baka\isJson;
use Canvas\Models\Apps;
use Canvas\Models\Companies;
use Canvas\Models\SystemModules;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Mvc\Model\ResultsetInterface;

class Rules extends BaseModel
{
    public int $systems_modules_id;
    public int $companies_id = 0;
    public int $apps_id = 1;
    public int $rules_types_id;
    public float $weight = 0;
    public string $name;
    public ?string $description = null;
    public string $pattern;
    public ?string $params = null;
    public int $is_async = 1;

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
                'alias' => 'rulesActions',
                'order' => 'weight|ASC'
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
    public static function getByModelAndRuleType(ModelInterface $model, RulesTypes $rulesTypes, Apps $apps) : ResultsetInterface
    {
        $systemModules = SystemModules::getByModelName(get_class($model));

        $bind = [
            'systems_module_id' => $systemModules->getId(),
            'rules_types_id' => $rulesTypes->getId(),
            'companies_id' => Companies::GLOBAL_COMPANIES_ID,
            'global_companies' => Companies::GLOBAL_COMPANIES_ID,
            'global_apps_id' => Apps::CANVAS_DEFAULT_APP_ID,
            'apps_id' => $apps->getId()
        ];

        //if it has a company reference
        if (isset($model->companies)
            && is_object($model->companies)
            && !$model->companies instanceof Simple
        ) {
            $bind['companies_id'] = $model->companies->getId();
        }

        return  Rules::find([
            'conditions' => 'systems_modules_id = :systems_module_id: 
                                AND rules_types_id = :rules_types_id: 
                                AND companies_id in (:companies_id:, :global_companies:)
                                AND apps_id in (:global_apps_id:, :apps_id:)',
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

    /**
     * Is this rule async?
     * should it be run with the queue?
     *
     * @return bool
     */
    public function isAsync() : bool
    {
        return (bool) $this->is_async;
    }
}
