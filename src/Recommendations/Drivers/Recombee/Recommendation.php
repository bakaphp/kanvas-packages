<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendations\Drivers\Recombee;

use Baka\Contracts\Auth\UserInterface;
use Baka\Contracts\Database\ModelInterface;
use Kanvas\Packages\Recommendations\Contracts\Engine;
use Kanvas\Packages\Recommendations\Contracts\Recommendation as ContractRecommendations;
use Recombee\RecommApi\Requests as Reqs;

class Recommendation implements ContractRecommendations
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
     * Recommendation items for users.
     * https://docs.recombee.com/api.html#recommend-items-to-user.
     *
     * @param UserInterface $user
     * @param int $total
     * @param array $options
     *
     * @return array|null
     */
    public function itemsToUser(
        UserInterface $user,
        int $total,
        array $options
    ) : ?array {
        return $this->engine->connect()->send(
            new Reqs\RecommendItemsToUser(
                $user->getId(),
                $total,
                $options
            )
        );
    }

    /**
     * Recommendation items for items.
     *
     * @param ModelInterface $model
     * @param UserInterface $user
     * @param int $total
     * @param array $options
     *
     * @return array|null
     */
    public function itemsToItems(
        ModelInterface $model,
        UserInterface $user,
        int $total,
        array $options
    ) : ?array {
        return $this->engine->connect()->send(
            new Reqs\RecommendItemsToItem(
                $model->getId(),
                $user->getId(),
                $total,
                $options
            )
        );
    }
}
