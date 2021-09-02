<?php

namespace Kanvas\Packages\Recommendation\Src;

use Exception;
use Kanvas\Packages\Recommendation\Contracts\Database as DatabaseInterface;
use Kanvas\Packages\Recommendation\Contracts\Engine;
use Kanvas\Packages\Recommendation\Contracts\Interactions;
use Kanvas\Packages\Recommendation\Contracts\Items;
use Phalcon\Di\DiInterface;


class Database implements DatabaseInterface
{
    protected Engine $client;

    public final function __construct(DiInterface $container)
    {
        $engine = $container->get('recommendation');

        if (!$engine instanceOf Engine) {
            throw new Exception('Not a recommendation driver');
        }

        $this->client = $container->get('recommendation')->connect($this->getSource());

    }

    public function getClient(): Engine {
        return $this->client;
    }

    public function create() : self {
        return $this;
    }

    public function delete() : bool {
        return false;
    }
    
    public function interactions(): Interactions {
        return new Interactions();
    }
    public function items() : Items {
        return new Items();
    }

    public function getSource() {

    }

    public function getRecommendationForEntity($entity, float $rotation) {}
    public function getRecommendationForUser($user, float $rotation) {}
    public function refresh($memo) {}

}