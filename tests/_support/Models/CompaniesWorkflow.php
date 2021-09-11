<?php
declare(strict_types=1);

namespace Kanvas\Packages\Test\Support\Models;

use Canvas\Models\Companies as KanvasCompanies;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;

class CompaniesWorkflow extends KanvasCompanies implements WorkflowsEntityInterfaces
{
    public function afterRules() : void
    {
    }
}
