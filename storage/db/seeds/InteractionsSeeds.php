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
                'id' => 1,
                'name' => 'like',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'name' => 'save',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'name' => 'comment',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'name' => 'reply',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 5,
                'name' => 'following',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 6,
                'name' => 'followers',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $posts = $this->table('interactions');
        $posts->insert($data)
              ->save();
    }
}
