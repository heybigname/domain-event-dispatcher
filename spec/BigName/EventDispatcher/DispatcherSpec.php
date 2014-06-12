<?php

namespace spec\BigName\EventDispatcher;

use Illuminate\Container\Container;
use BigName\EventDispatcher\Event;
use BigName\EventDispatcher\Listener;
use BigName\EventDispatcher\ListenerIsNotValid;
use BigName\EventDispatcher\Stubs\ReportSent;
use BigName\EventDispatcher\Stubs\StubListener;
use BigName\EventDispatcher\Stubs\UserCreated;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DispatcherSpec extends ObjectBehavior
{
    function let(Container $container)
    {
        $container->make(Argument::type('string'))->willReturn(new StubListener);

        $this->beConstructedWith($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('BigName\EventDispatcher\Dispatcher');
    }

    function it_allows_to_add_a_listener(Listener $listener)
    {
        $this->addListener('EventName', $listener);
        $this->hasListeners('EventName')->shouldReturn(true);

        $this->addListener('OtherEventName', 'BigName\EventDispatcher\Stubs\SendEmailListener');
        $this->hasListeners('OtherEventName')->shouldReturn(true);
    }

    function it_does_not_accept_invalid_listeners()
    {
        $this->shouldThrow(new ListenerIsNotValid)->duringAddListener('EventName', 123);
        $this->shouldThrow(new ListenerIsNotValid)->duringAddListener('EventName', []);
        $this->shouldThrow(new ListenerIsNotValid)->duringAddListener('EventName', 12.3);
        $this->shouldThrow(new ListenerIsNotValid)->duringAddListener('EventName', function() {});
        $this->shouldThrow(new ListenerIsNotValid)->duringAddListener('EventName', new \stdClass);
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

    function it_dispatches_with_a_container_created_listener(Event $event)
    {
        $this->addListener('EventName', 'BigName\EventDispatcher\Stubs\StubListener');
        $event->getName()->willReturn('EventName');
        $this->dispatch($event);
    }
}
