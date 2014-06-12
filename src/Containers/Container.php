<?php namespace BigName\EventDispatcher\Containers;

interface Container {
    public function make($class);
} 