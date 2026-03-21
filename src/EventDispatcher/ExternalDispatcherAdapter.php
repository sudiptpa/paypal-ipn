<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\EventDispatcher;

use InvalidArgumentException;
use Sujip\PayPal\Notification\Contracts\DispatcherInterface;

class ExternalDispatcherAdapter implements DispatcherInterface
{
    public function __construct(
        private readonly object $dispatcher,
    ) {
        if (!is_callable([$this->dispatcher, 'addListener']) || !is_callable([$this->dispatcher, 'dispatch'])) {
            throw new InvalidArgumentException(
                'Dispatcher must provide addListener() and dispatch() methods.'
            );
        }
    }

    public function addListener(string $eventName, callable $listener): void
    {
        call_user_func([$this->dispatcher, 'addListener'], $eventName, $listener);
    }

    public function dispatch(object $event, string $eventName): object
    {
        $result = call_user_func([$this->dispatcher, 'dispatch'], $event, $eventName);

        return is_object($result) ? $result : $event;
    }
}
