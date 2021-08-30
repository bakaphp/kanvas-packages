<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendation;

use Exception;
use Kanvas\Packages\Recommendation\Contracts\Engine;
use Kanvas\Packages\Recommendation\Contracts\Recomendation as RecomendationInterface;
use Phalcon\Di\DiInterface;

class Recomendation implements RecomendationInterface
{
    protected Engine $client;

    final public function __construct(DiInterface $container)
    {
        $engine = $container->get('recommendation');

        if (!$engine instanceof Engine) {
            throw new Exception('Not a recommendation driver');
        }

        $this->client = $container->get('recommendation')->connect($this->getSource());
    }

    public function getClient() : Engine
    {
        return $this->client;
    }

    public function create() : self
    {
        return $this;
    }

    public function delete() : bool
    {
        return false;
    }

    public function interactions() : Interactions
    {
        return new Interactions($this);
    }
    public function items() : Items
    {
        return new Items($this);
    }

    public function getSource()
    {
    }

    public function getRecommendationForEntity($entity, float $rotation)
    {
    }
    public function getRecommendationForUser($user, float $rotation)
    {
    }
    public function refresh($memo)
    {
    }
}
