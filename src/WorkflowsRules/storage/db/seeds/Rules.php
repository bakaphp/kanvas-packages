<?php

use Phinx\Seed\AbstractSeed;

class Rules extends AbstractSeed
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
        $this->table('rules')
            ->insert([
                [
                    'system_modules_id' => '1',
                    'rules_types_id' => '1',
                    'name' => 'test',
                    'pattern' => '1 AND 2'
                ]
            ])
            ->saveData();
    }
}
