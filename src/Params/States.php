<?php

namespace Dbt\ModelFactory\Params;

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
        $mapper = function (string $state): string {
            return sprintf('has%s', ucfirst($state));
        };

        $mutated = array_map($mapper, $states);

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
