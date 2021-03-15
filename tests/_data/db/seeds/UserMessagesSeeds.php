<?php

use Phinx\Seed\AbstractSeed;

class UserMessagesSeeds extends AbstractSeed
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
        try {
            $data = [
                [
                    'messages_id' => 1,
                    'users_id' => 1,
                    'is_deleted' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'messages_id' => 1,
                    'users_id' => 2,
                    'is_deleted' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                ]
            ];

            $posts = $this->table('user_messages');
            $posts->insert($data)
              ->save();
        } catch (Exception $e) {
        }
    }
}
