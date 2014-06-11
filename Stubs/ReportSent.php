<?php namespace BigName\EventDispatcher\Stubs;

use BigName\EventDispatcher\Event;

class ReportSent implements Event
{
    public function getName()
    {
        return 'ReportSent';
    }
}