<?php

namespace Dbt\ModelFactory\Params;

class Count implements Param
{
    /** @var int */
    private $value;

    public function __construct (int $howMany = 1)
    {
        $this->value = $howMany;
    }

    public static function of (int $howMany): self
    {
        return new self($howMany);
    }

    /**
     * Laravel's EloquentFactory will return a Collection if you request any
     * number of models, even if that number is 1. Providing it will null
     * instead will prevent this behaviour.
     * @return int|null
     */
    public function get ()
    {
        if ($this->value <= 1) {
            return null;
        }

        return $this->value;
    }
}
