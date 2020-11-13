<?php

use Phinx\Seed\AbstractSeed;

class RulesTypes extends AbstractSeed
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
        $this->table('rules_types')
            ->insert([
                [
                    'name' => 'created'
                ],
                [
                    'name' => 'updated'
                ],
                [
                    'name' => 'deleted'
                ]
            ])
            ->saveData();
    }
}
