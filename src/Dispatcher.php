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
}
