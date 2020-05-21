<?php

namespace Kanvas\Packages\Social\Models;

class Flags extends BaseModel
{
    public $id;
    public $name;
    public $weight;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'flags';
    }
}
