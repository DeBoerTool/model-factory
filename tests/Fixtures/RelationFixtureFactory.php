<?php

namespace Dbt\Tests\Fixtures;

use Dbt\ModelFactory\ModelFactory;

class RelationFixtureFactory extends ModelFactory
{
    protected $model = RelationFixture::class;

    public function definition (): array
    {
        return [
            'name' => $this->faker->sentence(3),
        ];
    }
}
