<?php


namespace Faker;


class DefaultGenerator
{
    protected $default;
    public function __call($method, $attributes)
    {
        return $this->default;
    }
}