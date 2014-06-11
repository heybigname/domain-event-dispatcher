<?php namespace BigName\EventDispatcher;

interface Listener
{
    public function handle(Event $event);
} 