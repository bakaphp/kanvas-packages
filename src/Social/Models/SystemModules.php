<?php

namespace Kanvas\Packages\Social\Models;

use Canvas\Models\SystemModules as KanvasSystemModules;
use Phalcon\Mvc\ModelInterface;
use Phalcon\Di;

class SystemModules extends KanvasSystemModules
{
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Get System Module by its model_name.
     *
     * @deprecated v2
     *
     * @param string $model_name
     *
     * @return ModelInterface
     */
    public static function getSystemModuleByModelName(string $modelName) : ModelInterface
    {
        $app = Di::getDefault()->get('app');
        $module = SystemModules::findFirst([
            'conditions' => 'model_name = ?0 and apps_id = ?1',
            'bind' => [
                $modelName,
                $app->getId()
            ]
        ]);

        if (!is_object($module)) {
            throw new InternalServerErrorException('No system module for ' . $modelName);
        }

        return $module;
    }
}
