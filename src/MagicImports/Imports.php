<?php 

namespace Kanvas\Packages\MagicImports;

use Kanvas\Packages\MagicImports\Contracts\ColumnsInterface;
use Phalcon\DI\Injectable;
use ReflectionClass;
use Exception;
use Phalcon\Mvc\Model;

/**
 * Class Structure
 */
class Imports extends Injectable
{
    /**
     * @var Kanvas\Package\MagicImports\Contracts\ColumnsInterface
     */
    public $structure;

    /**
     * Define if it is a test or is a real process
     * @var Boolean
     */
    public $commit;

    /**
     * namespace for the model
     * @var string
     */
    public $namespaceModel;

    /**
     * @param Phalcon\Mvc\Model $model
     * @param array $fields
     */
    function __construct(ColumnsInterface $structure, bool $commit)
    {
        $this->structure = $structure;
        $this->commit = $commit;
        $this->namespaceModel = (new ReflectionClass($this->structure->model))->getNamespaceName();
    }

    /**
     * process data from array
     * @param array $data
     */
    public function processData(array $data) : array
    {
        $processData = $this->structureData($data);

        $db = $this->di->get('db');
        $db->begin();
        $return = [
            "errors" => [],
            "success" => [],
        ];
        foreach ($processData as $modelData) {
            try {
                $models = $this->save($modelData);
                $relationships = $this->setRelationships($models);
                $return['success'][] = $this->getReturn(array_merge($models,$relationships));
            } catch (Exception $e) {
                $return['errors'][] = $e->getMessage();
            }
        }

        if($this->commit){
            $db->commit();
        }else{
            $db->rollback();
        }

        return $return;
    }

    /**
     * Save model data
     * @param array $modelData
     * @return array $models
     */
    public function save(array $modelData) : array
    {
        $return = [];
        foreach ($modelData as $model => $data) {
            $obj = new $model();

            if(method_exists($obj,'findFirstOrCreate')){
                $obj = $model::findFirstOrCreate(null, $data);
            }else{
                $obj->assign($data);
                $obj->saveOrFail();
            }
            
            $return[$this->getClassName($obj)] = $obj;
        }
        return $return;
    }

    /**
     * we organize the data
     * @param array $data
     * @return array
     */
    public function structureData(array $data) : array
    {
        $maps = $data['mapping']['map'];
        $fileValues = $data['fileValues'];
        $processData = [];

        foreach ($fileValues as $key => $data) {
            if(!is_array($data)){
                continue;
            }
            $processData[] = $this->getStructureData($data, $maps);
        }

        return $processData;
    }

    /**
     * Mapping data
     * @param array $raw
     * @param array $maps
     * @return array $processData
     */
    public function getStructureData($raw, $maps): array
    {
        $processData = [];

        foreach ($maps as $order => $value) {
            /**
             * 0 => Model
             * 1 => db tb name
             */
            $map = explode('.',$value);

            $processData["{$this->namespaceModel}\\{$map[0]}"][$map[1]] = $raw[$order];
        }

        return $processData;
    }

    /**
     * create relationships between all models
     * @param array $models
     * @return array $models
     */
    public function setRelationships(array $models) : array
    {
        /**
         * Get the import structure
         */
        $structures = $this->structure->getStructure();
        $returns = [];
        foreach ($structures as $modelName => $data) {
            if(!isset($data['relationships'])){
                continue;
            }

            if(!isset($models[$modelName])){
                continue;
            }
            
            /**
             * @var all-models $models
             * @var PrincipalModel $models[$modelName]
             * @var relationships $data['relationships']
             */
            $saveRelationships = $this->saveRelationships($models, $models[$modelName], $data['relationships']);
            $returns = array_merge($returns, $saveRelationships);
        }

        return $returns;
    }

    /**
     * Save relationships
     * @param array $models
     * @param Model $model
     * @param array $relationships
     * @return array $returns
     */
    public function saveRelationships($models, Model $model, $relationships) : array
    {
        $returns = [];
        foreach ($models as $class => $obj) {
            $className = get_class($obj);
           
            if(!isset($relationships[$className])){
                continue;
            }

            $primaryKey = $relationships[$className]['primaryKey'];
            $relationshipsKey = $relationships[$className]['relationshipsKey'];

            switch ($relationships[$className]['getType']) {
                case 0:
                case 1:
                    $model->{$primaryKey} = $obj->{$relationshipsKey};
                    $model->save();
                    $returns[$this->getClassName($model)] = $model;
                break;
                case 2:
                    $obj->{$relationshipsKey} = $model->{$primaryKey};
                    $obj->save();
                    $returns[$this->getClassName($obj)] = $obj;
                break;
                case 3:
                    $intermediateModel = $relationships[$className]['intermediateModel'];
                    $intermediateFields = $relationships[$className]['intermediateFields'];
                    $intermediateReferencedField = $relationships[$className]['intermediateReferencedField'];
                    $newObj = new $intermediateModel;
                    $newObj->{$intermediateFields} = $model->{$primaryKey};
                    $newObj->{$intermediateReferencedField} = $obj->{$relationshipsKey};
                    $newObj->save();
                    $returns[$this->getClassName($newObj)] = $newObj;
                break;
                default:
                    throw new Exception("Error Processing Request", 1);
                break;
            }
        }

        return $returns;
    }

    /**
     * get the class name from object
     * @param class $obj
     * @return string
     */
    public function getClassName($obj) : string
    {
        /**
         * Get namespace and class name
         */
        $class = explode("\\",get_class($obj));

        return end($class);
    }

    /**
     * Format array from frontend
     * @param array $models
     * @return array
     */
    public function getReturn($models) : array
    {
        $return = [];

        foreach ($models as $model => $data) {
            foreach ($data as $field => $value) {
                $return["{$model}.{$field}"] = $value;
            }
        }
        return $return;
    }
}