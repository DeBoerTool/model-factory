<?php

namespace Dbt\Tests\Fixtures;

use Dbt\ModelFactory\ModelFactory;

class ModelFixtureFactory extends ModelFactory
{
    protected $model = ModelFixture::class;

    public static $oneOfValues = [
        'here are',
        'some values',
        'that can be',
        'chosen by the',
        'oneOf() method',
    ];

    public static $maybeValues = [
        'yes',
        'no',
    ];

    /**
     * This is the main factory definition.
     * @return array
     */
    public function definition (): array
    {
        // Calling another Model Factory.
        $model = $this->factory(RelationFixture::class)->create();

        return [
            // Choose one of two values randomly.
            'maybe' => $this->maybe(...self::$maybeValues),
            // Choose one of many values randomly.
            'one_of' => $this->oneOf(self::$oneOfValues),
            'relation_id' => $model->id,
        ];
    }

    /**
     * This is a factory state.
     * @return array
     */
    public function hasState (): array
    {
        return [
            'state' => $this->faker->word,
        ];
    }
}
