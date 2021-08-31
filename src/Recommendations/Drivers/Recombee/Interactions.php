<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendation\Drivers\Recombee;

use Baka\Contracts\Auth\UserInterface;
use Baka\Contracts\ModelInterface;
use Kanvas\Packages\Recommendation\Contracts\Engine;
use Kanvas\Packages\Recommendation\Contracts\Interactions as ContractsInteractions;
use Phalcon\Utils\Slug;
use Recombee\RecommApi\Requests as Reqs;

class Interactions implements ContractsInteractions
{
    protected Engine $engine;

    /**
     * Constructor.
     *
     * @param Engine $engine
     */
    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Add Likes.
     *
     * @param UserInterface $user
     * @param ModelInterface $model
     * @param float $rating
     *
     * @return bool
     */
    public function likes(UserInterface $user, ModelInterface $model, float $rating = 1.0) : bool
    {
        return $this->rating($user, $model);
    }

    /**
     * Add rating.
     *
     * @param UserInterface $user
     * @param ModelInterface $model
     * @param float $rating
     *
     * @return bool
     */
    public function rating(UserInterface $user, ModelInterface $model, float $rating = 1.0) : bool
    {
        $key = Slug::generate($model->getSource());

        $this->engine::connect()->send(
            new Reqs\AddRating(
                $user->getId(),
                $key . $model->getId(),
                $rating,
                [
                    'timestamp' => time(),
                    'cascadeCreate' => true
                ]
            )
        );

        return true;
    }

    /**
     * Interaction view type.
     *
     * @param UserInterface $user
     * @param ModelInterface $model
     *
     * @return bool
     */
    public function view(UserInterface $user, ModelInterface $model, float $duration = 1) : bool
    {
        $key = Slug::generate($model->getSource());

        $this->engine->send(
            new Reqs\AddDetailView(
                $user->getId(),
                $key . $model->getId(),
                [
                    'duration' => $duration,
                    'timestamp' => time(),
                    'cascadeCreate' => true
                ]
            )
        );

        return true;
    }

    /**
     * Add user purchase.
     *
     * @param UserInterface $user
     * @param ModelInterface $model
     *
     * @return bool
     */
    public function purchase(UserInterface $user, ModelInterface $model, array $purchase) : bool
    {
        $key = Slug::generate($model->getSource());

        $this->engine->send(
            new Reqs\AddPurchase(
                $user->getId(),
                $key . $model->getId(),
                [
                    'price' => $purchase['price'],
                    'amount' => $purchase['amount'],
                    'timestamp' => time(),
                    'cascadeCreate' => true
                ]
            )
        );

        return true;
    }

    /**
     * Add to cart.
     *
     * @param UserInterface $user
     * @param ModelInterface $model
     *
     * @return bool
     */
    public function addToCart(UserInterface $user, ModelInterface $model, array $purchase) : bool
    {
        $key = Slug::generate($model->getSource());

        $this->engine->send(
            new Reqs\AddPurchase(
                $user->getId(),
                $key . $model->getId(),
                [
                    'price' => $purchase['price'],
                    'amount' => $purchase['amount'],
                    'timestamp' => time(),
                    'cascadeCreate' => true
                ]
            )
        );

        return true;
    }

    /**
     * Add a item to a book mark.
     *
     * @param UserInterface $user
     * @param ModelInterface $model
     *
     * @return bool
     */
    public function bookmark(UserInterface $user, ModelInterface $model) : bool
    {
        $key = Slug::generate($model->getSource());

        $this->engine->send(
            new Reqs\AddBookmark(
                $user->getId(),
                $key . $model->getId(),
                [
                    'timestamp' => time(),
                    'cascadeCreate' => true
                ]
            )
        );

        return true;
    }
}
