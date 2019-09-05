[![Build Status](https://travis-ci.org/DeBoerTool/model-factory.svg?branch=master)](https://travis-ci.org/DeBoerTool/model-factory)
[![Latest Stable Version](https://poser.pugx.org/dbt/model-factory/v/stable)](https://packagist.org/packages/dbt/model-factory)
[![License](https://poser.pugx.org/dbt/model-factory/license)](https://packagist.org/packages/dbt/model-factory)


# Class-based Model Factories for Laravel

This package is alternative to keeping your model factories in plain PHP files. 

## Getting Started
### Prerequisites

This package requires PHP 7.1.3 or higher, `illuminate/support@^5.7`, and `illuminate/database@^5.7`.

### Installing

Via Composer:

```bash
composer require dbt/model-factory
``` 

### Testing

Run:

```bash
composer test
```

## Configuring

Publish the `model-factory.php` configuration file with `php artisan vendor:publish` command, or copy the file from this repository. The service provider should be auto-discovered by Laravel.

A model factory looks like this:

```php
use Dbt\ModelFactory\ModelFactory;

class MyModelFactory extends ModelFactory
{
    protected $model = MyModel::class;

    /**
     * This is the main factory definition.
     * @return array
     */
    public function definition (): array
    {
        return [
            'my_string_column' => $this->faker->name,
        ];
    }

    /**
     * This will happen after the model is created.
     * @return void
     */
    public function after (MyModel $model): void
    {
        // Do some stuff to the model.
    }

    /**
     * This is a factory state.
     * @return array
     */
    public function myState (): array
    {
        return [
            'my_int_column' => rand(1, 10),
        ];
    }

     /**
      * This will happen after the model is created in the given state.
      * @return void
      */
     public function afterMyState (MyModel $model): void
     {
         // Do some stuff to the model.
     }
}
```

To register your model factory, include it in the config file:

```php
'classes' => [
    // ...
    MyModelFactory::class,
];
```

## Usage

### With the `factory(...)` function

Your model factories will be registered with Laravel as usual, so they can be called with Laravel's global `factory()` function:

```php
// Factory without state.
$model = factory(MyModel::class)->create();

// Factory with state.
$modelWithState = factory(MyModel::class)->states('myState')->create();
```

### With the `Create` class

If you prefer a slightly more expressive way to create models for testing, try out the `Create` class:

```php
$model = Create::a(new MyModel, new Count(10), new States('myState'), new Overrides(['column' => 'value'])); 
```

The method definition requires a model followed by variadic `Param`s, in any order, and in any combination.

```php
// Create a model with defaults.
$model = Create::a(new MyModel);

// The following are all equivalent:
Create::a(new MyModel, new Count(...), new States(...), new Overrides(...));
Create::a(new MyModel, new States(...), new Count(...), new Overrides(...));
Create::a(new MyModel, new States(...), new Overrides(...), new Count(...));

// Etc.
``` 

## License

MIT. Do as you wish.
