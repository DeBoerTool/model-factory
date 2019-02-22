<?php

namespace Dbt\ModelFactory;

interface IModelFactory
{
    /**
     * The default definition.
     * @return array
     */
    public function definition () : array;

    /**
     * Register the models factories.
     * @return mixed
     */
    public function register ();
}
