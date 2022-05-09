<?php

namespace Dbt\ModelFactory;

use Dbt\ModelFactory\Params\Count;
use Dbt\ModelFactory\Params\Overrides;
use Dbt\ModelFactory\Params\Param;
use Dbt\ModelFactory\Params\States;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Create a model or a collection of models with a more expressive syntax.
 * This is an (probably slightly slower) alternative to calling Laravel's
 * factory(...) method.
 */
class Create
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     */
    public static function a (Model $model, Param ...$params)
    {
        /** @var EloquentFactory $factory */
        $factory = app(EloquentFactory::class);

        $count = self::parseParams(new Count(), $params);
        $overrides = self::parseParams(new Overrides(), $params);
        $states = self::parseParams(new States(), $params);
        $modelClass = get_class($model);

        /**
         * There's no great way to annotate what self::parseParams(...) is
         * actually doing to let's just suppress these errors.
         *
         * @psalm-suppress PossiblyInvalidArgument
         */
        return $factory->of($modelClass)
            ->states($states->get())
            ->times($count->get())
            ->create($overrides->get());
    }

    /**
     * This is essentially the same as Create::a(...) but with slightly
     * different semantics.
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     */
    public static function some (Model $model, Count $count, Param ...$params)
    {
        return self::a($model, $count, ...$params);
    }

    private static function parseParams (Param $default, array $args): Param
    {
        $class = get_class($default);

        foreach ($args as $arg) {
            if ($arg instanceof $class) {
                return $arg;
            }
        }

        return $default;
    }
}
