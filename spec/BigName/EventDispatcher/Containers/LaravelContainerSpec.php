<?php

namespace spec\BigName\EventDispatcher\Containers;

use BigName\EventDispatcher\Stubs\StubListener;
use Illuminate\Container\Container;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LaravelContainerSpec extends ObjectBehavior
{
    function let(Container $container, StubListener $listener)
    {
        $container->make(Argument::type('string'))->willReturn($listener);

        $this->beConstructedWith($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('BigName\EventDispatcher\Containers\Container');
    }

    function it_creates_a_listener()
    {
        $this->make('BigName\EventDispatcher\Stubs\StubListener')->shouldReturnAnInstanceOf('BigName\EventDispatcher\Stubs\StubListener');
    }
}
