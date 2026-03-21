<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Events;

use Sujip\PayPal\Notification\Payload;

abstract class Verification
{
    public function __construct(
        private readonly Payload $payload,
    ) {
    }

    public function getPayload(): Payload
    {
        return $this->payload;
    }
}
