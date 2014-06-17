<?php

namespace spec\BigName\EventDispatcher;

use BigName\EventDispatcher\Containers\Container;
use BigName\EventDispatcher\Event;
use BigName\EventDispatcher\LazyListenersIsNotValid;
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

    function it_allows_to_add_listeners(Listener $listener)
    {
        $this->addListener('EventName', $listener);
        $this->hasListeners('EventName')->shouldReturn(true);
        $this->addListener('OtherEventName', $listener);
        $this->hasListeners('OtherEventName')->shouldReturn(true);
    }

    function it_allows_to_add_lazy_listeners()
    {
        $this->addLazyListener('EventName', 'BigName\EventDispatcher\Stubs\StubListener');
        $this->hasLazyListeners('EventName')->shouldReturn(true);
    }

    function it_throws_when_adding_an_invalid_lazy_listener()
    {
        $this->shouldThrow(new LazyListenersIsNotValid)->duringAddLazyListener('EventName', 123);
        $this->shouldThrow(new LazyListenersIsNotValid)->duringAddLazyListener('EventName', []);
        $this->shouldThrow(new LazyListenersIsNotValid)->duringAddLazyListener('EventName', 12.3);
        $this->shouldThrow(new LazyListenersIsNotValid)->duringAddLazyListener('EventName', null);
        $this->shouldThrow(new LazyListenersIsNotValid)->duringAddLazyListener('EventName', new \stdClass);
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

    function it_can_get_lazy_listeners()
    {
        $this->addLazyListener('EventName', 'BigName\EventDispatcher\Stubs\StubListener');
        $this->addLazyListener('EventName', 'BigName\EventDispatcher\Stubs\StubListener');
        $this->getLazyListeners('EventName')->shouldHaveCount(2);
        $this->getLazyListeners('EventName')->shouldBeArray();
    }

    function it_can_get_any_listeners(Listener $listener)
    {
        $this->addListener('EventName', $listener);
        $this->addLazyListener('EventName', 'BigName\EventDispatcher\Stubs\StubListener');
        $this->getAnyListeners('EventName')->shouldHaveCount(2);
        $this->getAnyListeners('EventName')->shouldBeArray();
    }

    function it_returns_an_empty_array_when_no_listeners_found()
    {
        $this->getListeners('EventName')->shouldHaveCount(0);
    }

    function it_returns_an_empty_array_when_no_lazy_listeners_found()
    {
        $this->getLazyListeners('EventName')->shouldHaveCount(0);
    }

    function it_returns_an_empty_array_if_there_are_no_listeners_of_any_kind()
    {
        $this->getAnyListeners('EventName')->shouldHaveCount(0);
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

    function it_dispatches_with_lazy_listeners(Event $event)
    {
        $this->addLazyListener('EventName', 'BigName\EventDispatcher\Stubs\StubListener');
        $event->getName()->willReturn('EventName');
        $this->dispatch($event);
    }
}
