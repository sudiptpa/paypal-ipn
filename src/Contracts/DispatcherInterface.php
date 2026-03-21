<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Contracts;

interface DispatcherInterface
{
    public function addListener(string $eventName, callable $listener): void;

    public function dispatch(object $event, string $eventName): object;
}
