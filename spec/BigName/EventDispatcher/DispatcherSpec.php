<?php

namespace spec\BigName\EventDispatcher;

use BigName\EventDispatcher\Stubs\ReportSent;
use BigName\EventDispatcher\Stubs\UserCreated;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use BigName\EventDispatcher\Listener;

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
        $this->getListeners('EventName')->shouldBeArray();
    }

    function it_returns_an_empty_array_when_no_listeners_found()
    {
        $this->getListeners('EventName')->shouldHaveCount(0);
    }

    function it_dispatches_one_event(Listener $listener)
    {
        $this->addListener('UserCreated', $listener);

        $event = new UserCreated;
        $this->dispatch($event);

        $listener->handle($event)->shouldHaveBeenCalled();
    }

    function it_dispatches_multiple_events(Listener $listener, Listener $otherListener)
    {
        $this->addListener('UserCreated', $listener);
        $this->addListener('ReportSent', $otherListener);

        $event = new UserCreated;
        $otherEvent = new ReportSent;

        $this->dispatch([$event, $otherEvent]);

        $listener->handle($event)->shouldHaveBeenCalled();
        $otherListener->handle($otherEvent)->shouldHaveBeenCalled();
    }

    function it_dispatches_with_an_empty_array()
    {
        $this->dispatch([]);
    }
}
