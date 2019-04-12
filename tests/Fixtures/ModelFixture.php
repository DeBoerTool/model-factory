<?php

namespace Dbt\Tests\Fixtures;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class ModelFixture extends EloquentModel
{
    protected $table = 'one';

    public function relation (): BelongsTo
    {
        return $this->belongsTo(
            RelationFixture::class,
            'relation_id'
        );
    }
}
