<?php


use Phinx\Seed\AbstractSeed;

class ReactionsSeeds extends AbstractSeed
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
                'name' => 'confuse',
                'icon' => "☹",
                'apps_id' => 1,
                'companies_id' => 1,
                'is_deleted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'smile',
                'icon' => '☺',
                'apps_id'=> 1,
                'companies_id' => 1,
                'is_deleted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ], [
                'name' => 'confuse',
                'icon' => "☹",
                'apps_id' => 1,
                'companies_id' => 2,
                'is_deleted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'smile',
                'icon' => '☺',
                'apps_id'=> 1,
                'companies_id' => 2,
                'is_deleted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ], [
                'name' => 'confuse',
                'icon' => "☹",
                'apps_id' => 1,
                'companies_id' => 3,
                'is_deleted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'smile',
                'icon' => '☺',
                'apps_id'=> 1,
                'companies_id' => 3,
                'is_deleted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $posts = $this->table('reactions');
        $posts->insert($data)
              ->save();
    }
}
