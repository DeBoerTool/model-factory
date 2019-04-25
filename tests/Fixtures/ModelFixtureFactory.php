<?php

namespace Dbt\Tests\Fixtures;

use Closure;
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
     * This method will be called after creating the model.
     * @param \Dbt\Tests\Fixtures\ModelFixture $model
     */
    public function after (ModelFixture $model): void
    {
        $relation = $this->factory(RelationFixture::class)->create();

        $model->relation()->associate($relation->id);
        $model->save();
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

    /**
     * This method will be called after creating the model with state.
     * @param \Dbt\Tests\Fixtures\ModelFixture $model
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
     * @param \Dbt\Tests\Fixtures\ModelFixture $model
     */
    public function afterHasMoreState (ModelFixture $model): void
    {
        $model->state_after = $this->faker->word;
        $model->save();
    }
}
