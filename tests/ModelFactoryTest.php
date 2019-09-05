<?php /** @noinspection PhpIncompatibleReturnTypeInspection */

namespace Dbt\Tests;

use Dbt\ModelFactory\Create;
use Dbt\ModelFactory\Params\Count;
use Dbt\ModelFactory\Params\Overrides;
use Dbt\ModelFactory\Params\States;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Dbt\Tests\Fixtures\ModelFixture as Fixture;
use Dbt\Tests\Fixtures\RelationFixture;
use Dbt\Tests\Fixtures\ModelFixtureFactory;

class ModelFactoryTest extends IntegrationTestCase
{
    private $class = Fixture::class;

    /** @test */
    public function creating_a_model ()
    {
        /** @var Fixture $model */
        $model = Create::a(new Fixture());

        $this->assertInstanceOf($this->class, $model);
        $this->assertOneOf(ModelFixtureFactory::$maybeValues, $model->maybe);
        $this->assertOneOf(ModelFixtureFactory::$oneOfValues, $model->one_of);
        $this->assertNull($model->state);
        $this->assertInstanceOf(RelationFixture::class, $model->relation);
    }

    /** @test */
    public function creating_a_model_with_state ()
    {
        /** @var Fixture $model */
        $model = Create::a(new Fixture(), States::has('state'));

        $this->assertNotNull($model->state);
        $this->assertNotNull($model->state_after);
    }

    /** @test */
    public function creating_a_model_with_a_random_string ()
    {
        $make = function (): Fixture
        {
            return Create::a(new Fixture(), States::has('randomStringState'));
        };

        $model1 = $make();
        $model2 = $make();

        // Since the underlying rs() method delegates to Str::random() and that
        // is well-tested, we don't actually test the randomness of it here.
        $this->assertIsString($model1->state);
        $this->assertIsString($model2->state);
        $this->assertNotSame($model1->state, $model2->state);
    }

    /** @test */
    public function creating_a_model_with_a_random_int ()
    {
        $make = function (): Fixture
        {
            return Create::a(new Fixture(), States::has('randomIntState'));
        };

        $model1 = $make();
        $model2 = $make();

        // Since the underlying ri() method delegates to rand(), we don't
        // actually test randomness.
        $this->assertIsInt($model1->state);
        $this->assertIsInt($model2->state);
        $this->assertNotSame($model1->state, $model2->state);
    }

    /** @test */
    public function creating_a_model_with_a_random_float ()
    {
        $make = function (): Fixture
        {
            return Create::a(new Fixture(), States::has('randomFloatState'));
        };

        $model1 = $make();
        $model2 = $make();

        // Since the underlying rf() method delegates to Faker and that is well-
        // tested, we don't actually test randomness here.
        $this->assertIsFloat($model1->state);
        $this->assertIsFloat($model2->state);
        $this->assertNotSame($model1->state, $model2->state);
    }

    /** @test */
    public function creating_a_model_with_state_callback ()
    {
        /** @var Fixture $model */
        $model = Create::a(new Fixture(), States::has('moreState'));

        $this->assertNull($model->state);
        $this->assertNotNull($model->state_after);
    }

    /** @test */
    public function failing_to_create_a_model_with_nonexistent_state ()
    {
        $this->expectException(InvalidArgumentException::class);

        Create::a(new Fixture(), new States('shouldFail'));
    }

    /** @test */
    public function creating_a_model_or_a_collection_of_models (): void
    {
        $model = Create::a(new Fixture(), Count::of(1));

        $this->assertInstanceOf(Fixture::class, $model);

        $collection = Create::a(new Fixture(), Count::of(2));

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(2, $collection);
    }

    /** @test */
    public function creating_a_model_with_overrides (): void
    {
        $expected = Str::random(16);

        /** @var Fixture $model */
        $model = Create::a(new Fixture(), Overrides::of(['maybe' => $expected]));

        $this->assertSame($model->maybe, $expected);
    }

    /** @test */
    public function creating_with_all_three_params (): void
    {
        $override = Str::random(16);
        $count = 5;

        $created = Create::a(
            new Fixture(),
            Count::of($count),
            States::of('hasState'),
            Overrides::of(['maybe' => $override])
        );

        $this->assertCount($count, $created);
        $this->assertSame($created->first()->maybe, $override);
        $this->assertNotNull($created->first()->state);
        $this->assertNotNull($created->first()->state_after);
    }

    public function assertOneOf (array $possibilities, $value)
    {
        $this->assertContains($value, $possibilities);
    }
}
