<?php
declare(strict_types=1);

namespace Kanvas\Packages\Test\Support\Models;

use Canvas\Models\Companies as KanvasCompanies;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;
use Kanvas\Packages\WorkflowsRules\Traits\CanUseRules;

class CompaniesWorkflow extends KanvasCompanies implements WorkflowsEntityInterfaces
{
    use CanUseRules;

    public function afterRules() : void
    {
    }
}
