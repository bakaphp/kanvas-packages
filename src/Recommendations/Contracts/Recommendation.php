<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendations\Contracts;

use Baka\Contracts\Auth\UserInterface;
use Baka\Contracts\ModelInterface;

interface Recommendation
{
    public function itemsToUser(
        UserInterface $user,
        int $total,
        array $options
    ) : ?array;

    public function itemsToItems(
        ModelInterface $model,
        UserInterface $user,
        int $total,
        array $options
    ) : ?array;
}
