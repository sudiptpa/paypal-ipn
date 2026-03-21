<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Tests;

use PHPUnit\Framework\TestCase;
use Sujip\PayPal\Notification\Contracts\Service;
use Sujip\PayPal\Notification\Http\Request;
use Sujip\PayPal\Notification\Http\Response;
use Sujip\PayPal\Notification\Payload;

final class RequestTest extends TestCase
{
    public function testRequestUsesInjectedServiceDirectly(): void
    {
        $payload = new Payload(['txn_id' => '123']);

        $service = new class implements Service {
            public ?Payload $received = null;

            public function call(Payload $payload): Response
            {
                $this->received = $payload;

                return new Response('VERIFIED', 200);
            }
        };

        $request = new Request($service);
        $response = $request->call($payload);

        $this->assertSame($payload, $service->received);
        $this->assertSame('VERIFIED', $response->getBody());
        $this->assertSame(200, $response->getCode());
    }

    public function testRequestRejectsUnsupportedTransportObjects(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Request transport must be null, a Service implementation, or a Guzzle client instance.');

        new Request(new \stdClass());
    }
}
