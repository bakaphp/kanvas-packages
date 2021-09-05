<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendations\Contracts;

use Baka\Contracts\Auth\UserInterface;
use Baka\Contracts\ModelInterface;

interface Interactions
{
    public function __construct(Engine $engine);
    public function likes(UserInterface $user, ModelInterface $model) : bool;
    public function rating(UserInterface $user, ModelInterface $model, float $rating = 1.0) : bool;
    public function view(UserInterface $user, ModelInterface $model, float $duration = 1) : bool;
    public function purchase(UserInterface $user, ModelInterface $model, array $purchase) : bool;
    public function addToCart(UserInterface $user, ModelInterface $model, array $purchase) : bool;
    public function bookmark(UserInterface $user, ModelInterface $model) : bool;
}
