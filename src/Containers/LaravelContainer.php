<?php namespace BigName\EventDispatcher\Containers;

use Illuminate\Container\Container as IlluminateContainer;
use Prophecy\Exception\InvalidArgumentException;

class LaravelContainer implements Container
{
    private $container;

    public function __construct(IlluminateContainer $container)
    {
        $this->container = $container;
    }

    public function make($class)
    {
        if ( ! is_string($class)) {
            throw new InvalidArgumentException;
        }
        return $this->container->make($class);
    }
}
