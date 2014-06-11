# Event Dispatcher

An Event Dispatcher built with a focus on Domain Events.

> So... this doesn't actually all work yet. K byethanks

## Installation

Begin by installing the package through Composer. Edit your project's `composer.json` file to require `bigname/event-dispatcher`.

  ```php
  "require": {
    "bigname/event-dispatcher": "0.1.x"
  }
  ```

Next use Composer to update your project from the the Terminal:

  ```php
  php composer.phar update
  ```

Once the package has been installed you'll need to add the service provider. Open your `app/config/app.php` configuration file, and add a new item to the `providers` array.

  ```php
  'BigName\EventDispatcher\Laravel\EventDispatcherServiceProvider'
  ```

## How It Works

### Event
In your domain you'll create an event, for let's say when a new user has been added.
Lets call this event `UserCreatedEvent`. This event will hold the necessary information for the listener to fulfill it's job.
You have complete freedom about which arguments it takes, since you'll be the one passing them in.
In some ways this event is a `Date Transfer Object` (DTO).

For example:

```php
<?php namespace Domain\Accounts\Events;

use BigName\EventDispatcher\Event;

class UserCreatedEvent implements Event
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function getName()
    {
        return 'UserCreated';
    }
}
```

### Listener
An event without a listener does no good for us, so lets create an email listener `MailNewlyCreatedUserListener` for the event `UserCreatedEvent`.

```php
<?php namespace Domain\Accounts\EventListeners;

use BigName\EventDispatcher\Listener;

class MailNewlyCreatedUserListener implements Listener
{
    private $mailer

    public function __construct(Mailer $mailer)
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
Now we got the building blocks ready lets start listening for some new users, shall we.
For the sake of this example, the code is kept as simple as possible.

```php
use BigName\EventDispatcher\Dispatcher;
use Domain\Accounts\Events\UserCreatedEvent;
use Domain\Accounts\EventListeners\MailNewlyCreatedUserListener;

// Listening for event
$mailer = // Some mail package...
$listener = new MailNewlyCreatedUserListener($mailer);

$dispatcher = new Dispatcher;
$dispatcher->addListener('UserCreated', $listener);

// Dispatching event
$user = // A wild user appeared..
$event = new UserCreatedEvent($user);

$dispatcher->dispatch($event);
```

### Dispatching multiple
For extra hipster points you can dispatch multiple events in 1 call.

```php
use BigName\EventDispatcher\Dispatcher;
use Domain\Accounts\UserAddedEvent;
use Domain\Achievements\UserGotAchievementEvent;

$user = ...;
$achievement = ...;
$events = [
    new UserAddedEvent($user),
    new UserGotAchievementEvent($user, $achievement)
];

$dispatcher = new Dispatcher;
$dispatcher->dispatch($events);
```

## That's it!
Later tater
