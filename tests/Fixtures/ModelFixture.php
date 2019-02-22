<?php

namespace Dbt\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    protected $table = 'test_table';
}
