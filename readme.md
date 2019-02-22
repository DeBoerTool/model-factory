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

## Usage

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
     * This is a factory state.
     * @return array
     */
    public function myState (): array
    {
        return [
            'my_int_column' => rand(1, 10),
        ];
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

Then you can use it as usual:

```php
// Factory without state.
$base = factory(MyModel::class)->create();

// Factory with state.
$state = factory(MyModel::class)->states('myState')->create();
```

### License

MIT. Do as you wish.
