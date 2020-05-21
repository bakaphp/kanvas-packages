<?php

namespace Kanvas\Packages\Social\Models;

class Interactions extends BaseModel
{
    public $id;
    public $name;
    public $title;
    public $icon;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'interactions';
    }
}
