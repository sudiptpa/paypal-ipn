<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Tests;

use PHPUnit\Framework\TestCase;
use Sujip\PayPal\Notification\Payload;

final class PayloadTest extends TestCase
{
    public function testPayloadCanBeCreatedFromArray(): void
    {
        $payload = new Payload([
            'txn_id' => 'abc123',
            'payment_status' => 'Completed',
        ]);

        $this->assertSame('abc123', $payload->find('txn_id'));
        $this->assertSame([
            'txn_id' => 'abc123',
            'payment_status' => 'Completed',
        ], $payload->all());
    }

    public function testPayloadCanBeCreatedFromRawQueryString(): void
    {
        $payload = new Payload('txn_id=abc123&payment_status=Completed');

        $this->assertSame('abc123', $payload->find('txn_id'));
        $this->assertSame('Completed', $payload->find('payment_status'));
        $this->assertSame('txn_id=abc123&payment_status=Completed', (string) $payload);
    }

    public function testMissingPayloadKeysReturnNull(): void
    {
        $payload = new Payload([]);

        $this->assertNull($payload->find('missing'));
    }
}
