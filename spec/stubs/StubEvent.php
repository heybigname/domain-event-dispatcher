<?php namespace BigName\EventDispatcher\Stubs;

use BigName\EventDispatcher\Event;

class StubEvent implements Event
{
    public function getName()
    {
        return 'StubEvent';
    }
}