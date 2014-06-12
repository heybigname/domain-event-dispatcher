<?php

namespace spec\BigName\EventDispatcher\Containers;

use BigName\EventDispatcher\Stubs\StubListener;
use Illuminate\Container\Container;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Exception\InvalidArgumentException;

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

    function it_creates_an_object()
    {
        $this->make('BigName\EventDispatcher\Stubs\StubListener')->shouldReturnAnInstanceOf('BigName\EventDispatcher\Stubs\StubListener');
    }

    function it_only_accepts_a_string_when_making_an_object()
    {
        $this->shouldThrow('InvalidArgumentException')->duringMake(123);
        $this->shouldThrow('InvalidArgumentException')->duringMake([]);
        $this->shouldThrow('InvalidArgumentException')->duringMake(12.3);
        $this->shouldThrow('InvalidArgumentException')->duringMake(new \stdClass);
    }
}
