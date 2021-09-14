<?php

namespace Kanvas\Packages\Tests\Integration\Workflows;

use Canvas\Models\Apps;
use Canvas\Models\Companies;
use Faker\Factory;
use Faker\Provider\DateTime;
use Faker\Provider\en_US\Address;
use Faker\Provider\en_US\Company;
use Faker\Provider\en_US\Person;
use Faker\Provider\en_US\PhoneNumber;
use IntegrationTester;
use Kanvas\Packages\Test\Support\Models\CompaniesWorkflow;
use Kanvas\Packages\WorkflowsRules\Jobs\RulesJob;
use Kanvas\Packages\WorkflowsRules\Models\Rules;
use Kanvas\Packages\WorkflowsRules\Models\RulesTypes as ModelsRulesTypes;
use Kanvas\Packages\WorkflowsRules\Models\Test;
use Kanvas\Packages\WorkflowsRules\Rules as RulesServices;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class RulesCest
{
    public function getRulesForEntity(IntegrationTester $I) : void
    {
        $rules = Rules::getByModelAndRuleType(Companies::findFirst(), ModelsRulesTypes::findFirst(), Apps::findFirst());

        $I->assertGreaterOrEquals(0, $rules->count());
    }

    public function setARuleInJobProcess(IntegrationTester $I) : void
    {
        $rule = Rules::findFirstOrFail();

        $ruleService = new RulesServices($rule);

        $I->assertInstanceOf(RulesServices::class, $ruleService);
        $I->assertTrue(!empty($ruleService->getPattern()));
    }

    public function getExpressionLanguage(IntegrationTester $I) : void
    {
        $rule = Rules::findFirstOrFail();
        $ruleService = new RulesServices($rule);

        $expression = $ruleService->getExpressionCondition();

        $I->assertArrayHasKey('expression', $expression);
        $I->assertArrayHasKey('values', $expression);
    }

    public function runExpressionLanguage(IntegrationTester $I) : void
    {
        $rule = Rules::findFirstOrFail();
        $ruleService = new RulesServices($rule);

        $expressionLanguage = new ExpressionLanguage();
        list('expression' => $expression, 'values' => $values) = $ruleService->getExpressionCondition();
        $values['created_at'] = date('Y-m-d H:i:s');

        //validate the expression and values with symfony expression language
        $result = $expressionLanguage->evaluate(
            $expression,
            $values
        );

        $I->assertIsBool($result);
    }

    public function executeRule(IntegrationTester $I) : void
    {
        $rule = Rules::findFirstOrFail();
        $ruleService = new RulesServices($rule);
        $company = CompaniesWorkflow::findFirstOrFail();

        $execute = $ruleService->execute($company, []);

        $I->assertTrue($execute->getLogs()->count() > 0);
        $I->assertTrue($execute->getLogs()->actionLogs->count() > 0);
    }

    public function rulesEntitySerialization(IntegrationTester $I) : void
    {
        $company = CompaniesWorkflow::findFirstOrFail();
        $company->setRulesRelatedEntities(1, 2, 3);

        $companySerialize = serialize($company);
        $companyUnSerialize = unserialize($companySerialize);

        $I->assertEquals(
            $company->getRulesRelatedEntities(),
            $companyUnSerialize->getRulesRelatedEntities()
        );
    }

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
}
