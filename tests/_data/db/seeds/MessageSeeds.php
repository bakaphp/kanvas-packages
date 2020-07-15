<?php


use Phinx\Seed\AbstractSeed;

class MessageSeeds extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'id' => 0,
                'apps_id' => 1,
                'companies_id' => 1,
                'users_id' => 1,
                'message_types_id' => 1,
                'message' => 'test message',
                'reactions_count' => 0,
                'comments_count' => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $posts = $this->table('messages');
        $posts->insert($data)
              ->save();
    }
}
