<?php

namespace Dbt\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string state_after
 * @property mixed maybe
 * @property mixed one_of
 * @property mixed state
 * @property mixed relation
 */
class ModelFixture extends Model
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
