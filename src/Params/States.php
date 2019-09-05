<?php

namespace Dbt\ModelFactory\Params;

use Illuminate\Support\Str;

class States implements Param
{
    /** @var string[] */
    private $states;

    public function __construct (string ...$states)
    {
        $this->states = $states;
    }

    public static function of (string ...$states): self
    {
        return new self(...$states);
    }

    public static function has (string ...$states): self
    {
        $mutated = array_map(function (string $state) {
            return sprintf('has%s', ucfirst($state));
        }, $states);

        return self::of(...$mutated);
    }

    /**
     * @return array
     */
    public function get ()
    {
        return $this->states;
    }
}
