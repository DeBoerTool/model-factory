<?php

namespace Dbt\ModelFactory;

use Carbon\Carbon;
use Faker\Generator;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Database\Eloquent\FactoryBuilder;

abstract class ModelFactory implements IModelFactory
{
    /** @const string */
    const DEFINITION = 'definition';

    /**
     * @var string
     * @psalm-suppress PropertyNotSetInConstructor
     */
    protected $model;

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

    abstract public function definition () : array;

    public function register (): void
    {
        foreach ($this->methods() as $method) {
            if ($method->name === self::DEFINITION) {
                $this->registerBaseDefinition();
                continue;
            }

            $this->registerState($method);
        }
    }

    protected function factory (string $model) : FactoryBuilder
    {
        return $this->factory->of($model);
    }

    /**
     * @param mixed $yes
     * @param mixed $no
     * @return mixed
     */
    protected function maybe ($yes, $no = null)
    {
        return rand(0, 1) === 1 ? $yes : $no;
    }

    /**
     * @param array $items
     * @return mixed
     */
    protected function oneOf (array $items)
    {
        return Arr::random($items);
    }

    private function registerState (ReflectionMethod $method): void
    {
        $this->factory->state(
            $this->model,
            $method->name,
            [$this, $method->name]
        );
    }

    private function registerBaseDefinition (): void
    {
        $this->factory->define(
            $this->model,
            [$this, self::DEFINITION]
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
