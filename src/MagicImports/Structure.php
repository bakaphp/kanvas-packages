<?php 

namespace Kanvas\Packages\MagicImports;

use Phalcon\DI\Injectable;
use Phalcon\Mvc\Model;
use Kanvas\Packages\MagicImports\Contracts\ColumnsInterface;
use Phalcon\Mvc\Model\Relation;
use Phalcon\Mvc\ModelInterface;

/**
 * Class Structure
 */
class Structure extends Injectable implements ColumnsInterface 
{
    /**
     * @var Phalcon\Mvc\Model
     */
    public $model;

    /**
     * @var array
     */
    public $fields;

    /**
     * @var array
     */
    public $relationship;

    /**
     * @var string
     */
    public $class_base;

    /**
     * @var string
     */
    public $raw;

    /**
     * @var array
     */
    public $exclude_fields = [
        'is_deleted',
        'created_at',
        'updated_at',
        'id',
    ];

    /**
     * @param Phalcon\Mvc\Model $model
     * @param array $fields
     * @param array $relationship
     */
    function __construct(ModelInterface $model, $fields = [], $relationship = [])
    {
        $this->model = $model; 
        $this->fields = $fields;
        $this->relationship = $relationship;
        $this->class_base = get_class($model); 
    }

    /**
     * Get structure for import
     * @return array $fields
     */
    public function getStructure() : array
    {
        $fields = $this->getFieldsByModel();
        return array_merge($fields, $this->fields);
    }

    /**
     * Get fields by models
     * @return array $raw
     */
    public function getFieldsByModel() : array
    {
        $this->raw = [];
        /**
         * Get namespace and class name
         */
        $class = explode("\\",$this->class_base);

        /**
         * Get Structure for the main class
         */
        $this->raw[end($class)] = $this->setStructure($this->model);

        /**
         * Set relationship
         */
        $relationships = $this->setRelationships($this->class_base);
        
        $externalRelationship = isset($this->relationship[end($class)]) ? $this->relationship[end($class)] : [];

        $this->raw[end($class)]['relationships'] = array_merge($relationships, $externalRelationship);

        return $this->raw;
    }

    /**
     * Set model relationships
     * @param string $classBase
     * @param boolean 
     */
    public function setRelationships(string $classBase, $recursive = true) : array
    {
        $relationships = [];
        /**
         * Get relations from the models
         */
        foreach ($this->modelsManager->getRelations($classBase) as $relations) {
            /**
             * Name of relationship
             * @var string
             */
            $relationshipName = $relations->getReferencedModel();

            $relationshipClass = explode("\\",$relationshipName);

            /**
             * Get Structure for the relationship class
             */
            $this->raw[end($relationshipClass)] = $this->setStructure(new $relationshipName);

            $relationships[$relationshipName] = $this->getRelationshipsKeys($relations);

            /**
             * @todo fixed
             */
            if($recursive && $classBase != $relationshipName){
                $relationshipFromRelationship = $this->setRelationships($relationshipName, false);
                $externalRelationship = isset($this->relationship[end($relationshipClass)]) ? $this->relationship[end($relationshipClass)] : [];

                $this->raw[end($relationshipClass)]['relationships'] = array_merge($relationshipFromRelationship, $externalRelationship);
            }
        }
        return $relationships;
    }

    /**
     * Format models data to arrays
     * @param Phalcon\Mvc\Model $model
     * @return array $raw
     */
    public function setStructure(ModelInterface $model): array
    {
        $raw = [];
        
        foreach ($model->toArray() as $tbname => $value) {
            if(in_array($tbname, $this->exclude_fields)){
                continue;
            }
            $raw['columns'][] = [
                "field" => $tbname,
                "type" => gettype($tbname),
                "validation" => false,
                "label" => ucfirst(str_replace('_',' ', $tbname))
            ];
        }
        
        return $raw;
    }

    /**
     * get primary keys from Relationships
     * @param $relationships
     */
    public function getRelationshipsKeys(Relation $relationships) : array
    {
        $keys = [];

        /**
         * Name of relationship
         * @var string
         */
        $keys['relationshipName'] = $relationships->getReferencedModel();

        /**
         * Relationships Types
         * 1 => hasOne
         * 2 => hasMany
         * @var int
         */
        $keys['getType'] = $relationships->getType();

        /**
         * Primary key from the models
         * @var string
         */
        $keys['primaryKey'] = $relationships->getFields();

        /**
         * relationships
         */
        $keys['relationshipsKey'] = $relationships->getReferencedFields();

        /**
         * Variable that defines if it is unique
         */
        //$model = new $keys['relationshipName'];
        //$pk = $model->getModelsMetaData()->getPrimaryKeyAttributes($model);
        //$keys['unique'] = $pk;

        return $keys;
    }
}