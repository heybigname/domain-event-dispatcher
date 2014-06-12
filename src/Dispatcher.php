<?php namespace BigName\EventDispatcher;

use BigName\EventDispatcher\Containers\Container;

class Dispatcher
{
    private $listeners = [];
    private $container;

    public function __construct(Container $container = null)
    {
        $this->container = $container;
    }


    public function addListener($name, $listener)
    {
        $this->isValidListener($listener);
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
        return array_map(function($listener) {
            if (is_string($listener)) {
                $listener = $this->container->make($listener);
            }
            return $listener;
        }, $this->listeners[$name]);
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
        foreach ($this->getListeners($event->getName()) as $listener) {
            $listener->handle($event);
        }
    }

    private function isValidListener($listener)
    {
        if ( ! ($listener instanceof Listener || is_string($listener))) {
            throw new ListenerIsNotValid;
        }
    }
}
