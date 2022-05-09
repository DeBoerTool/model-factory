<?php

namespace Dbt\ModelFactory;

use Carbon\Carbon;
use Faker\Generator;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Database\Eloquent\FactoryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

abstract class ModelFactory implements IModelFactory
{
    use RandomTrait;

    /** @const string */
    const DEFINITION = 'definition';

    /** @const string */
    const AFTER = 'after';

    /** @var string */
    protected $model = 'You should override this value.';

    /** @var \Illuminate\Database\Eloquent\Factory */
    protected $factory;

    /** @var \Faker\Generator */
    protected $faker;

    /** @var array */
    private $exclude = [
        '__construct',
        'register',
        'model',
    ];

    /** @var \Carbon\Carbon */
    protected $carbon;

    public function __construct (Factory $factory, Generator $faker, Carbon $carbon)
    {
        $this->factory = $factory;
        $this->faker = $faker;
        $this->carbon = $carbon;
    }

    abstract public function definition (): array;

    public function register (): void
    {
        foreach ($this->methods() as $method) {
            switch (true) {
                case $method->name === self::DEFINITION:
                    $this->registerDefinition();
                    break;
                case $method->name === self::AFTER:
                    $this->registerAfter();
                    break;
                case Str::contains($method->name, self::AFTER):
                    $this->registerAfterState($method);
                    break;
                default:
                    $this->registerState($method);
            }
        }
    }

    protected function factory (string $model): FactoryBuilder
    {
        return $this->factory->of($model);
    }

    protected function createOne (string $model, array $attributes = []): Model
    {
        return $this->factory($model)->create($attributes);
    }

    /**
     * @param  mixed  $yes
     * @param  mixed  $no
     * @return mixed
     */
    protected function maybe ($yes, $no = null)
    {
        return rand(0, 1) === 1 ? $yes : $no;
    }

    /**
     * @param  array  $items
     * @return mixed
     */
    protected function oneOf (array $items)
    {
        return Arr::random($items);
    }

    private function registerDefinition (): void
    {
        $this->factory->define(
            $this->model,
            [$this, self::DEFINITION]
        );
    }

    private function registerAfter (): void
    {
        /**
         * This error can only be triggered if no after() method has been set,
         * but we'll never get here if that's the case.
         *
         * @psalm-suppress InvalidArgument *
         */
        $this->factory->afterCreating(
            $this->model,
            [$this, self::AFTER]
        );
    }

    private function registerState (ReflectionMethod $method): void
    {
        $this->factory->state(
            $this->model,
            $method->name,
            [$this, $method->name]
        );
    }

    private function registerAfterState (ReflectionMethod $method): void
    {
        $state = Str::camel(
            str_replace(self::AFTER, '', $method->name)
        );

        $this->factory->afterCreatingState(
            $this->model,
            $state,
            [$this, $method->name]
        );
    }

    private function methods (): array
    {
        $reflector = new ReflectionClass(static::class);

        return array_filter(
            $reflector->getMethods(ReflectionMethod::IS_PUBLIC),
            function (ReflectionMethod $method): bool {
                return $method->isPublic()
                    && !in_array($method->name, $this->exclude);
            }
        );
    }
}
