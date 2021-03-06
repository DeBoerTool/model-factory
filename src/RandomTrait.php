<?php

namespace Dbt\ModelFactory;

use Faker\Provider\Base as Faker;
use Illuminate\Support\Str;

trait RandomTrait
{
    public static function rs (int $length): string
    {
        return Str::random($length);
    }

    public static function ri (int $min, int $max): int
    {
        return rand($min, $max);
    }

    public static function rf (
        int $min,
        int $max,
        int $maxDecimals = null
    ): float {
        return Faker::randomFloat($maxDecimals, $min, $max);
    }
}
