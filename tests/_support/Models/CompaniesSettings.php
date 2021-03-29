<?php
declare(strict_types=1);

namespace Kanvas\Packages\Test\Support\Models;

class CompaniesSettings extends BaseModel
{
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('companies_settings');
    }
}
