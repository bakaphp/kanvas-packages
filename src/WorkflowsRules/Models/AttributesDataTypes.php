<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

class AttributesDataTypes extends BaseModel
{
    public string $name;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->setSource('attributes_data_types');

        $this->hasMany(
            'id',
            Attributes::class,
            'attributes_data_types_id',
            ['alias' => 'attributes']
        );

        $this->hasMany(
            'id',
            AttributesOperators::class,
            'attributes_data_types_id',
            ['alias' => 'attributes']
        );
    }
}
