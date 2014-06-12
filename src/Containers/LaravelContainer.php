<?php namespace BigName\EventDispatcher\Containers;

use Illuminate\Container\Container as IlluminateContainer;

class LaravelContainer implements Container
{
    private $container;

    public function __construct(IlluminateContainer $container)
    {
        $this->container = $container;
    }

    public function make($class)
    {
        return $this->container->make($class);
    }
}
