<?php namespace BigName\EventDispatcher;

use BigName\EventDispatcher\Containers\Container;

class Dispatcher
{
    private $listeners = [];
    private $lazyListeners = [];
    private $container;

    public function __construct(Container $container = null)
    {
        $this->container = $container;
    }


    public function addListener($name, Listener $listener)
    {
        $this->listeners[$name][] = $listener;
    }

    public function addLazyListener($name, $listener)
    {
        if ( ! is_string($listener)) {
            throw new LazyListenersIsNotValid;
        }
        $this->lazyListeners[$name][] = $listener;
    }

    public function hasListeners($name)
    {
        return isset($this->listeners[$name]);
    }

    public function hasLazyListeners($name)
    {
        return isset($this->lazyListeners[$name]);
    }

    public function getAnyListeners($name)
    {
        return array_merge($this->getListeners($name), $this->getLazyListeners($name));
    }

    public function getListeners($name)
    {
        if ( ! $this->hasListeners($name)) {
            return [];
        }
        return $this->listeners[$name];
    }

    public function getLazyListeners($name)
    {
        if ( ! $this->hasLazyListeners($name)) {
            return [];
        }
        return array_map(function($listener) {
            return $this->container->make($listener);
        }, $this->lazyListeners[$name]);
    }

    public function dispatch($events)
    {
        if (is_array($events)) {
            $this->fireEvents($events);
            return;
        }
        $this->fireEvent($events);
    }

    private function fireEvents(array $events)
    {
        foreach ($events as $event) {
            $this->fireEvent($event);
        }
    }

    private function fireEvent(Event $event)
    {
        foreach ($this->getAnyListeners($event->getName()) as $listener) {
            $listener->handle($event);
        }
    }
}
