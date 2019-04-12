<?php

namespace Dbt\Tests\Fixtures;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class RelationFixture extends EloquentModel
{
    protected $table = 'two';

    public function test (): BelongsTo
    {
        return $this->belongsTo(ModelFixture::class, 'test_id');
    }
}
