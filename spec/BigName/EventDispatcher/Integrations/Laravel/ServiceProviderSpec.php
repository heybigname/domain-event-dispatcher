<?php

namespace spec\BigName\EventDispatcher\Integrations\Laravel;

use Illuminate\Foundation\Application;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ServiceProviderSpec extends ObjectBehavior
{
    function let(Application $application)
    {
        $this->beConstructedWith($application);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('BigName\EventDispatcher\Integrations\Laravel\ServiceProvider');
    }

    function it_binds_the_laravel_container()
    {
        $this->register();
    }

    function it_provides_the_laravel_container()
    {
        $this->provides()->shouldBeEqualTo(['BigName\EventDispatcher\Containers\Container']);
    }
}
