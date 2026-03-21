<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Tests;

use PHPUnit\Framework\TestCase;
use Sujip\PayPal\Notification\Contracts\Service;
use Sujip\PayPal\Notification\Events\Failure;
use Sujip\PayPal\Notification\Events\Verified;
use Sujip\PayPal\Notification\Handler\ArrayHandler;
use Sujip\PayPal\Notification\Http\Response;
use Sujip\PayPal\Notification\Payload;

final class ArrayHandlerTest extends TestCase
{
    public function testHandleReturnsManagerAndPayloadRemainsAccessible(): void
    {
        $handler = new ArrayHandler([
            'foo' => 'bar',
            'bar' => 'baz',
        ]);

        $this->assertInstanceOf(ArrayHandler::class, $handler);
        $this->assertSame('bar', $handler->getPayload()->create()->find('foo'));
        $this->assertSame('foo=bar&bar=baz', (string) $handler->getPayload()->create());
    }

    public function testSetPayloadReplacesExistingPayload(): void
    {
        $handler = new ArrayHandler(['txn_id' => 'first']);
        $handler->setPayload(['txn_id' => 'second']);

        $this->assertSame('second', $handler->getPayload()->create()->find('txn_id'));
    }

    public function testCustomServiceCanBeInjected(): void
    {
        $service = new class implements Service {
            public function call(Payload $payload): Response
            {
                return new Response('VERIFIED', 200);
            }
        };

        $manager = (new ArrayHandler(['txn_id' => '123']))
            ->using($service)
            ->handle();

        $captured = null;
        $manager->onVerified(static function (Verified $event) use (&$captured): void {
            $captured = $event;
        });

        $event = $manager->fire();

        $this->assertInstanceOf(Verified::class, $event);
        $this->assertInstanceOf(Verified::class, $captured);
        $this->assertSame('123', $captured->getPayload()->find('txn_id'));
    }

    public function testUnexpectedStatusDispatchesFailure(): void
    {
        $service = new class implements Service {
            public function call(Payload $payload): Response
            {
                return new Response('MAYBE', 200);
            }
        };

        $manager = (new ArrayHandler(['txn_id' => '123']))
            ->using($service)
            ->handle();

        $captured = null;
        $manager->onError(static function (Failure $event) use (&$captured): void {
            $captured = $event;
        });

        $event = $manager->fire();

        $this->assertInstanceOf(Failure::class, $event);
        $this->assertInstanceOf(Failure::class, $captured);
        self::assertStringContainsString('Unexpected verification status encountered', (string) $captured->error());
    }
}
