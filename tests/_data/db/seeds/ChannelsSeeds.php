<?php


use Phinx\Seed\AbstractSeed;

class ChannelsSeeds extends AbstractSeed
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
                'name' => 'profile',
                'description' => 'Basic Profile',
                'slug' => 'profile',
                'entity_namespace' => "Kanvas\Packages\Test\Support\Models\Lead",
                'entity_id' => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $posts = $this->table('channels');
        $posts->insert($data)
              ->save();
    }
}
