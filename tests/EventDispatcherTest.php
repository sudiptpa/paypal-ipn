<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Tests;

use PHPUnit\Framework\TestCase;
use Sujip\PayPal\Notification\EventDispatcher\EventDispatcher;
use Sujip\PayPal\Notification\EventDispatcher\InteropEventDispatcher;

final class EventDispatcherTest extends TestCase
{
    public function testLocalDispatcherInvokesRegisteredListeners(): void
    {
        $dispatcher = new EventDispatcher();
        $received = [];

        $dispatcher->addListener('ipn:verified', static function (object $event) use (&$received): void {
            $received[] = $event;
        });

        $event = new \stdClass();
        $dispatched = $dispatcher->dispatch($event, 'ipn:verified');

        $this->assertSame($event, $dispatched);
        $this->assertCount(1, $received);
        $this->assertSame($event, $received[0]);
    }

    public function testInteropDispatcherUsesCompatibleExternalDispatcher(): void
    {
        $external = new class {
            /** @var array<string, list<callable>> */
            public array $listeners = [];

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
        };

        $dispatcher = new InteropEventDispatcher($external);
        $called = false;
        $event = new \stdClass();

        $dispatcher->addListener('ipn:verified', static function (object $dispatched) use (&$called, $event): void {
            $called = $dispatched === $event;
        });

        $dispatcher->dispatch($event, 'ipn:verified');

        $this->assertTrue($called);
    }
}
