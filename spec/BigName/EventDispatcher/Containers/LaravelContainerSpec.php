<?php

namespace spec\BigName\EventDispatcher\Containers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LaravelContainerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BigName\EventDispatcher\Containers\Container');
    }
}
