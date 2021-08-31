<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendation\Contracts;

use Baka\Contracts\ModelInterface;

interface Items
{
    public function __construct(Engine $engine);

    public function add(ModelInterface $model, callable $fn) : bool;

    /**
     * Add multiple records.
     *
     * @param array|Phalcon\Mvc\Model\ResultsetInterface¶ $models
     * @param callable $fn
     *
     * @return bool
     */
    public function addMultiple($models, callable $fn) : bool;
    public function delete(ModelInterface $model) : bool;

    /**
     * Return list of items.
     *
     * @return mixed
     */
    public function list(array $options);
}
