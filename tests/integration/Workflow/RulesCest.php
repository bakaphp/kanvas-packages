<?php

namespace Workflow;

use Baka\Jobs\Jobs\RulesJob;
use Kanvas\Packages\WorkflowsRules\Models\Rules;

class RulesCest
{
    public function _before(IntegrationTester $I)
    {
    }

    // tests
    public function tryToTest(IntegrationTester $I)
    {
    }

    public function rulesJobTest(IntegrationTester $I)
    {
        $rules = Rules::findFirstOrFail(['conditions' => 'name = test']);
        RulesJob::dispatch($rules, 'created');
    }
}
