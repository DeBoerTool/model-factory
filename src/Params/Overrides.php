<?php

namespace Dbt\ModelFactory\Params;

class Overrides implements Param
{
    /** @var string[] */
    private $overrides;

    public function __construct (array $overrides = [])
    {
        $this->overrides = $overrides;
    }

    public static function of (array $overrides): self
    {
        return new self($overrides);
    }

    /**
     * @return array
     */
    public function get ()
    {
        return $this->overrides;
    }
}
