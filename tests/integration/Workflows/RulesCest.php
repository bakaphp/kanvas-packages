<?php

namespace Kanvas\Packages\Tests\Integration\Workflows;

use IntegrationTester;
use Kanvas\Packages\WorkflowsRules\Jobs\RulesJob;
use Kanvas\Packages\WorkflowsRules\Models\Rules;
use Kanvas\Packages\WorkflowsRules\Models\Test;

class RulesCest
{
    public function rulesJob(IntegrationTester $I) : void
    {
        $rules = Rules::findFirstOrFail([
            'conditions' => 'name = "test"'
        ]);
        $test = new Test;
        $test->name = 'Ichigo';
        $test->city = 'Santo Domingo';
        $test->firstname = 'Frederick';
        $test->lastname = 'Peñalo';
        $test->phone = '829-379-6918';
        $test->email = 'frederickpeal@mctekk.com';
        RulesJob::dispatch($rules, 'created', $test);
    }
}
