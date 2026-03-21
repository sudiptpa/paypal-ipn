<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Tests;

use PHPUnit\Framework\TestCase;
use Sujip\PayPal\Notification\Contracts\Service;
use Sujip\PayPal\Notification\Events\Failure;
use Sujip\PayPal\Notification\Events\Invalid;
use Sujip\PayPal\Notification\Events\Verified;
use Sujip\PayPal\Notification\Handler\ArrayHandler;
use Sujip\PayPal\Notification\Http\Response;
use Sujip\PayPal\Notification\Payload;

final class ManagerTest extends TestCase
{
    public function testManagerDispatchesVerifiedEvent(): void
    {
        $service = new class implements Service {
            public function call(Payload $payload): Response
            {
                return new Response('VERIFIED');
            }
        };

        $manager = (new ArrayHandler(['txn_id' => 'verified']))
            ->using($service)
            ->handle();

        $events = [];
        $manager->onVerified(static function (Verified $event) use (&$events): void {
            $events[] = $event;
        });

        $result = $manager->fire();

        $this->assertInstanceOf(Verified::class, $result);
        $this->assertCount(1, $events);
        $this->assertSame('verified', $events[0]->getPayload()->find('txn_id'));
    }

    public function testManagerDispatchesInvalidEvent(): void
    {
        $service = new class implements Service {
            public function call(Payload $payload): Response
            {
                return new Response('INVALID');
            }
        };

        $manager = (new ArrayHandler(['txn_id' => 'invalid']))
            ->using($service)
            ->handle();

        $events = [];
        $manager->onInvalid(static function (Invalid $event) use (&$events): void {
            $events[] = $event;
        });

        $result = $manager->fire();

        $this->assertInstanceOf(Invalid::class, $result);
        $this->assertCount(1, $events);
        $this->assertSame('invalid', $events[0]->getPayload()->find('txn_id'));
    }

    public function testManagerDispatchesFailureWhenTransportThrows(): void
    {
        $service = new class implements Service {
            public function call(Payload $payload): Response
            {
                throw new \Sujip\PayPal\Notification\Exceptions\ServiceException('network failed');
            }
        };

        $manager = (new ArrayHandler(['txn_id' => 'failed']))
            ->using($service)
            ->handle();

        $events = [];
        $manager->onError(static function (Failure $event) use (&$events): void {
            $events[] = $event;
        });

        $result = $manager->fire();

        $this->assertInstanceOf(Failure::class, $result);
        $this->assertCount(1, $events);
        $this->assertSame('network failed', $events[0]->error());
    }
}
