# Event Dispatcher

An Event Dispatcher built with a focus on Domain Events.

## Installation

Begin by installing the package through Composer. Edit your project's `composer.json` file to require `heybigname/event-dispatcher`.

  ```php
  "require": {
    "heybigname/event-dispatcher": "0.1.*"
  }
  ```

Next use Composer to update your project from the the Terminal:

  ```php
  php composer.phar update
  ```

## How It Works

### Event
In your domain you'll create an event, for let's say when a new member has been registered.
Lets call this event `MemberWasRegistered`. This event will hold the necessary information for the listener to fulfill it's job.
You have complete freedom about which arguments it takes, since you'll be the one passing them in.
In some ways this event is a `Date Transfer Object` (DTO).

For example:

```php
<?php namespace Domain\Accounts\Events;

use BigName\EventDispatcher\Event;

class MemberWasRegistered implements Event
{
    public $member;

    public function __construct($member)
    {
        $this->member = $member;
    }

    public function getName()
    {
        return 'MemberWasRegistered';
    }
}
```

### Listener
An event without a listener does no good for us, so lets create an email listener `WelcomeNewlyRegisteredMemberListener` for the event `MemberWasRegistered`.

```php
<?php namespace Domain\Accounts\EventListeners;

use BigName\EventDispatcher\Listener;

class WelcomeNewlyRegisteredMemberListener implements Listener
{
    private $mailer

    public function __construct($mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(Event $event)
    {
        // Do something with the event
    }
}
```

Same rule with the listeners as the events, you have complete freedom with the arguments.
When an event is dispatched the `handle` method on the correct listeners will be called.

### Listening
Now we got the building blocks ready lets start listening for some new members, shall we.
For the sake of this example, the code is kept as simple as possible.

```php
use BigName\EventDispatcher\Dispatcher;
use Domain\Accounts\Events\MemberWasRegistered;
use Domain\Accounts\EventListeners\WelcomeNewlyRegisteredMemberListener;

// Listening for event
$mailer = // Some mail package...
$listener = new WelcomeNewlyRegisteredMemberListener($mailer);

$dispatcher = new Dispatcher;
$dispatcher->addListener('MemberWasRegistered', $listener);

// Dispatching event
$member = // A wild member appeared..
$event = new MemberWasRegistered($member);

$dispatcher->dispatch($event);
```

### Lazy Listening
It's possible to have your listeners lazy loaded. Merely pass a string with full namespace of the class.
Currently this only works with the Laravel (Illuminate) Container. Other containers can be supported, if requested.

```php
use Illuminate\Container\Container;
use BigName\EventDispatcher\Dispatcher;
use BigName\EventDispatcher\Containers\LaravelContainer;

$container = new LaravelContainer(new Container);
$dispatcher = new Dispatcher($container);

$dispatcher->addListener('MemberWasRegistered', 'Domain\Accounts\EventListeners\WelcomeNewlyRegisteredMemberListener');
```

This will construct and instantiate the listener(s) on `dispatch()` or `getListeners()`.
Lazy loading listeners can help mitigate the overhead if you have all your listeners instantiated on bootstrap.

### Dispatching multiple
For extra hipster points you can dispatch multiple events in 1 call.

```php
use BigName\EventDispatcher\Dispatcher;
use Domain\Accounts\MemberWasRegistered;
use Domain\Achievements\MemberEarnedAchievement;

$member = ...;
$achievement = ...;
$events = [
    new MemberWasRegistered($member),
    new MemberEarnedAchievement($member, $achievement)
];

$dispatcher = new Dispatcher;
$dispatcher->dispatch($events);
```

## That's it!
Later tater
