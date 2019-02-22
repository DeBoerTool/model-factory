<?php

namespace Dbt\Tests\Fixtures;

use Dbt\ModelFactory\ModelFactory;

class ModelFixtureFactory extends ModelFactory
{
    protected $model = ModelFixture::class;

    public function definition (): array
    {
        return [
            'name' => $this->faker->name,
        ];
    }

    public function hasDays (): array
    {
        return [
            'days' => rand(1, 10),
        ];
    }
}
