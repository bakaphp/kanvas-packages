<?php

namespace Kanvas\Packages\Tests\Integration\Workflows;

use Faker\Factory;
use Faker\Provider\DateTime;
use Faker\Provider\en_US\Address;
use Faker\Provider\en_US\Company;
use Faker\Provider\en_US\Person;
use Faker\Provider\en_US\PhoneNumber;
use IntegrationTester;
use Kanvas\Packages\Test\Support\Models\Lead;
use Kanvas\Packages\WorkflowsRules\Jobs\RulesJob;
use Kanvas\Packages\WorkflowsRules\Models\Rules;
use Kanvas\Packages\WorkflowsRules\Models\Test;

class RulesCest
{
    public function rulesJob(IntegrationTester $I) : void
    {
        $faker = $this->getFaker();

        $rules = Rules::findFirstOrFail([
            'conditions' => 'name = "test"'
        ]);
        $test = new Test;
        $test->name = $faker->name;
        $test->city = $faker->city;
        $test->firstname = $faker->firstName;
        $test->lastname = $faker->lastName;
        $test->phone = $faker->tollFreePhoneNumber;
        $test->email = $faker->email;
        $test->dob = $faker->dateTimeThisCentury($max = 'now', $timezone = null)->format('Y-m-d');
        $test->companies_id = 1;

        RulesJob::dispatch($rules, 'created', $test);
    }

    /**
     * getFaker.
     *
     * @return void
     */
    public function getFaker()
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));
        $faker->addProvider(new Address($faker));
        $faker->addProvider(new PhoneNumber($faker));
        $faker->addProvider(new Company($faker));
        $faker->addProvider(new DateTime($faker));
        return $faker;
    }

    public function triggerRule(IntegrationTester $I)
    {
        $lead = Lead::findFirst();
        $lead->fireRules('created');
    }
}
