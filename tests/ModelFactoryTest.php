<?php

namespace Dbt\Tests;

use Dbt\Tests\Fixtures\ModelFixture;

class ModelFactoryTest extends TestCase
{
    private $class = ModelFixture::class;

    /** @test */
    public function making_a_model ()
    {
        $model = factory($this->class)->create();

        $this->assertInstanceOf($this->class, $model);
        $this->assertGreaterThan(0, strlen($model->name));
        $this->assertNull($model->days);
    }

    /** @test */
    public function making_a ()
    {
        $model = factory($this->class)->states('hasDays')->create();

        $this->assertGreaterThan(0, $model->days);
    }
}
