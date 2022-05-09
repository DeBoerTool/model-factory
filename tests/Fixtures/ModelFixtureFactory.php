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
     *
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
            'state' => null,
            'state_after' => null,
        ];
    }

    /**
     * This method will be called after creating the model.
     *
     * @param  \Dbt\Tests\Fixtures\ModelFixture  $model
     */
    public function after (ModelFixture $model): void
    {
        $relation = $this->createOne(RelationFixture::class);

        $model->relation()->associate($relation)->save();
    }

    /**
     * This is a factory state.
     *
     * @return array
     */
    public function hasState (): array
    {
        return [
            'state' => $this->faker->word,
        ];
    }

    public function hasRandomStringState (): array
    {
        return [
            'state' => $this->rs(16),
        ];
    }

    public function hasRandomIntState (): array
    {
        return [
            'state' => $this->ri(1, 1000),
        ];
    }

    public function hasRandomFloatState (): array
    {
        return [
            'state' => $this->rf(1, 1000),
        ];
    }

    /**
     * This method will be called after creating the model with state.
     *
     * @param  \Dbt\Tests\Fixtures\ModelFixture  $model
     */
    public function afterHasState (ModelFixture $model): void
    {
        $model->state_after = $this->faker->word;
        $model->save();
    }

    /**
     * State callbacks can be used without an accompanying factory state. This
     * callback will be called regardless of the fact that there's no
     * `hasMoreState` method.
     *
     * @param  \Dbt\Tests\Fixtures\ModelFixture  $model
     */
    public function afterHasMoreState (ModelFixture $model): void
    {
        $model->state_after = $this->faker->word;
        $model->save();
    }
}
