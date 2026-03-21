<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Tests;

use PHPUnit\Framework\TestCase;
use Sujip\PayPal\Notification\Contracts\Service;
use Sujip\PayPal\Notification\Events\Failure;
use Sujip\PayPal\Notification\Events\Verified;
use Sujip\PayPal\Notification\Http\Response;
use Sujip\PayPal\Notification\Ipn;
use Sujip\PayPal\Notification\Manager;
use Sujip\PayPal\Notification\Payload;

final class IpnTest extends TestCase
{
    public function testModernFacadeSupportsDirectVerification(): void
    {
        $service = new class implements Service {
            public ?Payload $received = null;

            public function call(Payload $payload): Response
            {
                $this->received = $payload;

                return new Response('VERIFIED', 200);
            }
        };

        $captured = null;

        $result = Ipn::fromArray(['txn_id' => 'modern-verified'])
            ->sandbox()
            ->using($service)
            ->onVerified(static function (Verified $event) use (&$captured): void {
                $captured = $event;
            })
            ->verify();

        $this->assertInstanceOf(Verified::class, $result);
        $this->assertInstanceOf(Verified::class, $captured);
        $this->assertInstanceOf(Payload::class, $service->received);
        $this->assertSame('modern-verified', $service->received->find('txn_id'));
        $this->assertSame('modern-verified', $captured->getPayload()->find('txn_id'));
    }

    public function testModernFacadeSupportsRawPayloads(): void
    {
        $service = new class implements Service {
            public ?Payload $received = null;

            public function call(Payload $payload): Response
            {
                $this->received = $payload;

                return new Response('VERIFIED', 200);
            }
        };

        $result = Ipn::fromRaw('txn_id=raw-verified&payment_status=Completed')
            ->using($service)
            ->verify();

        $this->assertInstanceOf(Verified::class, $result);
        $this->assertInstanceOf(Payload::class, $service->received);
        $this->assertSame('raw-verified', $service->received->find('txn_id'));
        $this->assertSame('Completed', $service->received->find('payment_status'));
    }

    public function testModernFacadeCanStillExposeManagerForListenerStyleUsage(): void
    {
        $service = new class implements Service {
            public function call(Payload $payload): Response
            {
                return new Response('MAYBE', 200);
            }
        };

        $manager = Ipn::fromArray(['txn_id' => 'legacy-style'])
            ->using($service)
            ->handle();

        $captured = null;
        $manager->onError(static function (Failure $event) use (&$captured): void {
            $captured = $event;
        });

        $result = $manager->fire();

        $this->assertInstanceOf(Manager::class, $manager);
        $this->assertInstanceOf(Failure::class, $result);
        $this->assertInstanceOf(Failure::class, $captured);
        $this->assertSame('legacy-style', $captured->getPayload()->find('txn_id'));
    }
}
