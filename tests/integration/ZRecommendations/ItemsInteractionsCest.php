<?php
declare(strict_types=1);

namespace Kanvas\Packages\Tests\Integration\ZRecommendations;

use IntegrationTester;
use Kanvas\Packages\Recommendations\Drivers\Recombee\Engine;
use Kanvas\Packages\Recommendations\Drivers\Recombee\Interactions;
use Kanvas\Packages\Social\Models\Tags;
use Kanvas\Packages\Test\Support\Models\Users;
use Kanvas\Packages\Test\Support\Recommendations\Database\Topics;

class ItemsInteractionsCest
{
    public function like(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);
        $interactions = new Interactions($engine);

        $tags = Tags::findFirst();
        $user = Users::findFirst();

        $like = $interactions->likes($user, $tags);

        $I->assertTrue($like);
    }

    public function rating(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);
        $interactions = new Interactions($engine);

        $tags = Tags::findFirst();
        $user = Users::findFirst();

        $rate = $interactions->likes($user, $tags, 3);

        $I->assertTrue($rate);
    }

    public function view(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);
        $interactions = new Interactions($engine);

        $tags = Tags::findFirst();
        $user = Users::findFirst();

        $view = $interactions->view($user, $tags);

        $I->assertTrue($view);
    }

    public function bookmark(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);
        $interactions = new Interactions($engine);

        $tags = Tags::findFirst();
        $user = Users::findFirst();

        $bookmark = $interactions->bookmark($user, $tags);

        $I->assertTrue($bookmark);
    }

    public function addToCart(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);
        $interactions = new Interactions($engine);

        $tags = Tags::findFirst();
        $user = Users::findFirst();

        $addToCart = $interactions->addToCart(
            $user,
            $tags,
            [
                'price' => 10,
                'amount' => 10,
            ]
        );

        $I->assertTrue($addToCart);
    }

    public function purchase(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);
        $interactions = new Interactions($engine);

        $tags = Tags::findFirst();
        $user = Users::findFirst();

        $purchase = $interactions->purchase(
            $user,
            $tags,
            [
                'price' => 10,
                'amount' => 10,
            ]
        );

        $I->assertTrue($purchase);
    }
}
