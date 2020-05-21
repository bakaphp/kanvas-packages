<?php

namespace Kanvas\Packages\Social\Models;

class Tags extends BaseModel
{
    public $id;
    public $apps_id;
    public $companies_id;
    public $users_id;
    public $name;
    public $slug;
    public $weight;
    public $is_feature;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'tags';
    }
}
