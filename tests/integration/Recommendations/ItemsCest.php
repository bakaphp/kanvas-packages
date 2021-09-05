<?php
declare(strict_types=1);

namespace Kanvas\Packages\Tests\Integration\Payments\Plaid;

use IntegrationTester;
use Kanvas\Packages\Recommendations\Drivers\Recombee\Engine;
use Kanvas\Packages\Recommendations\Drivers\Recombee\Items;
use Kanvas\Packages\Social\Models\Tags;
use Kanvas\Packages\Test\Support\Recommendations\Database\Topics;

class ItemsCest
{
    public function add(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);
        $items = new Items($engine);
        $tags = Tags::findFirst();

        $createItems = $items->add(
            $tags,
            function (Tags $tag) : array {
                return [
                    'name' => $tag->name,
                    'slug' => $tag->name,
                    'users_id' => $tag->users_id,

                ];
            }
        );

        $I->assertTrue($createItems);
    }

    public function delete(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);
        $items = new Items($engine);
        $tags = Tags::findFirst();

        $deleteItem = $items->delete(
            $tags,
        );

        $I->assertTrue($deleteItem);
    }

    public function addMultiple(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);
        $items = new Items($engine);
        $tags = Tags::find(['limit' => 3]);

        $createItems = $items->addMultiple(
            $tags,
            function (Tags $tag) : array {
                return [
                    'name' => $tag->name,
                    'slug' => $tag->name,
                    'users_id' => $tag->users_id,

                ];
            }
        );

        $I->assertTrue($createItems);
    }

    public function list(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);
        $items = new Items($engine);

        $listItems = $items->list([]);

        $I->assertIsArray($listItems);
    }
}
