<?php

namespace Dbt\Tests;

use InvalidArgumentException;
use Dbt\Tests\Fixtures\ModelFixture;
use Dbt\Tests\Fixtures\RelationFixture;
use Dbt\Tests\Fixtures\ModelFixtureFactory;

class ModelFactoryTest extends TestCase
{
    private $class = ModelFixture::class;

    /** @test */
    public function creating_a_model ()
    {
        $model = factory($this->class)->create();

        $this->assertInstanceOf($this->class, $model);
        $this->assertOneOf(ModelFixtureFactory::$maybeValues, $model->maybe);
        $this->assertOneOf(ModelFixtureFactory::$oneOfValues, $model->one_of);
        $this->assertNull($model->state);
        $this->assertInstanceOf(RelationFixture::class, $model->relation);
    }

    /** @test */
    public function creating_a_model_with_state ()
    {
        $model = factory($this->class)->states('hasState')->create();

        $this->assertNotNull($model->state);
        $this->assertNotNull($model->state_after);
    }

    /** @test */
    public function creating_a_model_with_a_random_string ()
    {
        $model1 = factory($this->class)
            ->states('hasRandomStringState')
            ->create();

        $model2 = factory($this->class)
            ->states('hasRandomStringState')
            ->create();

        // Since the underlying rs() method delegates to Str::random() and that
        // is well-tested, we don't actually test the randomness of it here.
        $this->assertIsString($model1->state);
        $this->assertIsString($model2->state);
        $this->assertNotSame($model1->state, $model2->state);
    }

    /** @test */
    public function creating_a_model_with_a_random_int ()
    {
        $model1 = factory($this->class)
            ->states('hasRandomIntState')
            ->create();

        $model2 = factory($this->class)
            ->states('hasRandomIntState')
            ->create();

        // Since the underlying ri() method delegates to rand(), we don't
        // actually test randomness.
        $this->assertIsInt($model1->state);
        $this->assertIsInt($model2->state);
        $this->assertNotSame($model1->state, $model2->state);
    }

    /** @test */
    public function creating_a_model_with_a_random_float ()
    {
        $model1 = factory($this->class)
            ->states('hasRandomFloatState')
            ->create();

        $model2 = factory($this->class)
            ->states('hasRandomFloatState')
            ->create();

        // Since the underlying rf() method delegates to Faker and that is well-
        // tested, we don't actually test randomness here.
        $this->assertIsFloat($model1->state);
        $this->assertIsFloat($model2->state);
        $this->assertNotSame($model1->state, $model2->state);
    }

    /** @test */
    public function creating_a_model_with_state_callback ()
    {
        $model = factory($this->class)->states('hasMoreState')->create();

        $this->assertNull($model->state);
        $this->assertNotNull($model->state_after);
    }

    /** @test */
    public function failing_to_create_a_model_with_nonexistent_state ()
    {
        $this->expectException(InvalidArgumentException::class);

        factory($this->class)->states('thisShouldFail')->create();
    }

    public function assertOneOf (array $possibilities, $value)
    {
        $this->assertContains($value, $possibilities);
    }
}
