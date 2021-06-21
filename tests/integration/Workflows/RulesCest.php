<?php

namespace Kanvas\Packages\Tests\Integration\Workflows;

use Faker\Factory;
use Faker\Provider\DateTime;
use Faker\Provider\en_US\Address;
use Faker\Provider\en_US\Company;
use Faker\Provider\en_US\Person;
use Faker\Provider\en_US\PhoneNumber;
use IntegrationTester;
use Kanvas\Packages\Test\Support\Models\TestRule;
use Kanvas\Packages\Test\Support\Models\Users;
use Kanvas\Packages\WorkflowsRules\Jobs\RulesJob;
use Kanvas\Packages\WorkflowsRules\Models\Rules;
use Kanvas\Packages\WorkflowsRules\Services\Rules as RulesServices;
use Phalcon\Di;

class RulesCest
{
    public function directRuleTest(IntegrationTester $I) : void
    {
        $rules = Rules::findFirstOrFail([
            'conditions' => 'name = "test"'
        ]);

        Di::getDefault()->set('userData', Users::findFirst());

        $rule = RulesServices::set($rules);
        $rule->validate($this->getTestRule());
    }

    public function rulesJob(IntegrationTester $I) : void
    {
        $rules = Rules::findFirstOrFail([
            'conditions' => 'name = "test"'
        ]);

        RulesJob::dispatch($rules, 'created', $this->getTestRule());
    }

    /**
     * getFaker.
     *
     * @return TestRule
     */
    public function getTestRule() : TestRule
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));
        $faker->addProvider(new Address($faker));
        $faker->addProvider(new PhoneNumber($faker));
        $faker->addProvider(new Company($faker));
        $faker->addProvider(new DateTime($faker));

        $test = new TestRule;
        $test->name = $faker->name;
        $test->city = $faker->city;
        $test->firstname = $faker->firstName;
        $test->lastname = 'Rosario'; //$faker->lastName;
        $test->phone = $faker->tollFreePhoneNumber;
        $test->email = $faker->email;
        $test->dob = $faker->dateTimeThisCentury($max = 'now', $timezone = null)->format('Y-m-d');
        $test->companies_id = 1;

        return $test;
    }
}
