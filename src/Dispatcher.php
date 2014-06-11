<?php namespace BigName\EventDispatcher;

class Dispatcher
{
    private $listeners = [];

    public function addListener($name, Listener $listener)
    {
        $this->listeners[$name][] = $listener;
    }

    public function hasListeners($name)
    {
        return isset($this->listeners[$name]);
    }

    public function getListeners($name)
    {
        if ( ! $this->hasListeners($name)) {
            return [];
        }
        return $this->listeners[$name];
    }

    public function dispatch($event)
    {

    }
}
