<?php


use Phinx\Seed\AbstractSeed;

class InteractionsSeeds extends AbstractSeed
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
                'name' => 'react',
                'title' => 'react',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'save',
                'title' => 'save',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'comment',
                'title' => 'comment',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'reply',
                'title' => 'reply',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'following',
                'title' => 'following',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'followers',
                'title' => 'followers',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $posts = $this->table('interactions');
        $posts->insert($data)
              ->save();
    }
}
