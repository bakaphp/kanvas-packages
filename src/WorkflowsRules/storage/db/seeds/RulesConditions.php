<?php

use Phinx\Seed\AbstractSeed;

class RulesConditions extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $this->table('rules_conditions')
             ->insert([
                 [
                     'rules_id' => '1',
                     'attribute_name' => 'name',
                     'operator' => '==',
                     'value' => 'Ichigo'
                 ],
                 [
                     'rules_id' => '1',
                     'attribute_name' => 'city',
                     'operator' => '==',
                     'value' => 'Santo Domingo'
                 ]
             ])
             ->saveData();
    }
}
