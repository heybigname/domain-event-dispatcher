<?php namespace BigName\EventDispatcher\Stubs;

use BigName\EventDispatcher\Event;

class UserCreated implements Event
{
    public function getName()
    {
        return 'UserCreated';
    }
}