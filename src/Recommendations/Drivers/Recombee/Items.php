<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendations\Drivers\Recombee;

use Baka\Contracts\Database\ModelInterface;
use Kanvas\Packages\Recommendations\Contracts\Engine;
use Kanvas\Packages\Recommendations\Contracts\Items as ContractsItems;
use Phalcon\Utils\Slug;
use Recombee\RecommApi\Requests\DeleteItem;
use Recombee\RecommApi\Requests\ListItems;
use Recombee\RecommApi\Requests\SetItemValues;
use RuntimeException;

class Items implements ContractsItems
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
     * Add a new entity.
     *
     * @param ModelInterface $model
     * @param callable $fn
     *
     * @return bool
     */
    public function add(ModelInterface $model, callable $fn) : bool
    {
        $options = $fn($model);

        if (!is_array($options)) {
            throw new RuntimeException('Expected options results to be a array');
        }
        $options['created_at'] = strtotime($model->created_at);

        $key = Slug::generate($model->getSource());

        $this->engine->connect()->send(new SetItemValues(
            $key . $model->getId(),
            $options,
            [
                'cascadeCreate' => true //Use cascadeCreate for creating item
            ]
        ));

        return true;
    }

    /**
     * Add multiple records.
     *
     * @param array|Phalcon\Mvc\Model\ResultsetInterface $models
     * @param callable $fn
     *
     * @return bool
     */
    public function addMultiple($models, callable $fn) : bool
    {
        foreach ($models as $model) {
            $this->add($model, $fn);
        }

        return true;
    }

    /**
     * Delete element.
     *
     * @param ModelInterface $model
     *
     * @return bool
     */
    public function delete(ModelInterface $model) : bool
    {
        $key = Slug::generate($model->getSource());

        $this->engine->connect()->send(
            new DeleteItem($key . $model->getId())
        );

        return true;
    }

    /**
     * Get list of items.
     *
     * @param array $options
     *
     * @return void
     */
    public function list(array $options)
    {
        return $this->engine->connect()->send(
            new ListItems($options)
        );
    }
}
