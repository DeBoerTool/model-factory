<?php

namespace Dbt\Tests;

use Dbt\Tests\Fixtures\ModelFixture;
use Dbt\Tests\Fixtures\RelationFixture;
use Dbt\Tests\Fixtures\ModelFixtureFactory;
use InvalidArgumentException;

class ModelFactoryTest extends TestCase
{
    private $class = ModelFixture::class;

    /** @test */
    public function making_a_model ()
    {
        $model = factory($this->class)->create();

        $this->assertInstanceOf($this->class, $model);
        $this->assertOneOf(ModelFixtureFactory::$maybeValues, $model->maybe);
        $this->assertOneOf(ModelFixtureFactory::$oneOfValues, $model->one_of);
        $this->assertNull($model->state);
        $this->assertInstanceOf(RelationFixture::class, $model->relation);
    }

    /** @test */
    public function making_a_model_with_state ()
    {
        $model = factory($this->class)->states('hasState')->create();

        $this->assertNotNull($model->state);
        $this->assertNotNull($model->state_after);
    }

    /** @test */
    public function making_a_model_with_state_callback ()
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
