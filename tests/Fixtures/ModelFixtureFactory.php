<?php

namespace Dbt\Tests\Fixtures;

use Dbt\ModelFactory\ModelFactory;

class ModelFixtureFactory extends ModelFactory
{
    protected $model = ModelFixture::class;

    /**
     * This is the main factory definition.
     * @return array
     */
    public function definition (): array
    {
        return [
            'name' => $this->faker->name,
        ];
    }

    /**
     * This is a factory state.
     * @return array
     */
    public function hasDays (): array
    {
        return [
            'days' => rand(1, 10),
        ];
    }
}
