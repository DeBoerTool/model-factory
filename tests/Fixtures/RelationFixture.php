<?php

namespace Dbt\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RelationFixture extends EloquentModel
{
    protected $table = 'two';

    public function test (): BelongsTo
    {
        return $this->belongsTo(ModelFixture::class, 'test_id');
    }
}
