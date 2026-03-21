<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\EventDispatcher;

use Sujip\PayPal\Notification\Contracts\DispatcherInterface;

class ListenerDispatcher implements DispatcherInterface
{
    /** @var array<string, list<callable>> */
    private array $listeners = [];

    public function addListener(string $eventName, callable $listener): void
    {
        $this->listeners[$eventName] ??= [];
        $this->listeners[$eventName][] = $listener;
    }

    public function dispatch(object $event, string $eventName): object
    {
        foreach ($this->listeners[$eventName] ?? [] as $listener) {
            $listener($event);
        }

        return $event;
    }
}
