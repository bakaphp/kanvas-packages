<?php


use Phinx\Seed\AbstractSeed;

class ChannelsSeeder extends AbstractSeed
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
        $data = [
            [
                'name' => 'profile',
                'description' => 'Basic Profile',
                'last_message_id' => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $posts = $this->table('channels');
        $posts->insert($data)
              ->save();
    }
}
