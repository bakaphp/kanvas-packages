<?php

use Phinx\Seed\AbstractSeed;

class Actions extends AbstractSeed
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
        $this->table('actions')
            ->insert([
                [
                    'name' => 'SendToZoho',
                    'model_name' => 'Kanvas\Packages\WorkflowsRules\Actions\SendToZoho'
                ],
                [
                    'name' => 'SendMail',
                    'model_name' => 'Kanvas\Packages\WorkflowsRules\Actions\SendMail'
                ]
            ])
            ->saveData();
    }
}
