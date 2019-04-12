<?php

namespace Dbt\Tests;

use Dbt\Tests\Fixtures\ModelFixture;
use Dbt\Tests\Fixtures\ModelFixtureFactory;

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
    }

    /** @test */
    public function making_a_model_with_state ()
    {
        $model = factory($this->class)->states('hasState')->create();

        $this->assertNotNull($model->state);
    }

    public function assertOneOf (array $possibilities, $value)
    {
        $this->assertContains($value, $possibilities);
    }
}
