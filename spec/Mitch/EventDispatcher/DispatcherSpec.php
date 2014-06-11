<?php

namespace spec\BigName\EventDispatcher;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use BigName\EventDispatcher\Listener;
use BigName\EventDispatcher\Event;

class DispatcherSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BigName\EventDispatcher\Dispatcher');
    }

    function it_allows_to_add_a_listener(Listener $listener)
    {
        $this->addListener('EventName', $listener);
        $this->hasListeners('EventName')->shouldReturn(true);

        $this->addListener('OtherEventName', $listener);
        $this->hasListeners('OtherEventName')->shouldReturn(true);
    }

    function it_has_no_listeners()
    {
        $this->hasListeners('EventName')->shouldReturn(false);
    }

    function it_can_get_listeners(Listener $listener)
    {
        $this->addListener('EventName', $listener);
        $this->addListener('EventName', $listener);
        $this->getListeners('EventName')->shouldHaveCount(2);
    }

    function it_returns_an_empty_array_when_no_listeners_found()
    {
        $this->getListeners('EventName')->shouldHaveCount(0);
        $this->getListeners('EventName')->shouldBeArray();
    }

    function it_dispatches_one_event(Event $event)
    {
        $this->dispatch($event);
    }

    function it_dispatches_multiple_events(Event $event)
    {
        $this->dispatch([$event, $event]);
    }
}
