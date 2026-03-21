<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Payload;

use Sujip\PayPal\Notification\Contracts\Payload as PayloadContract;
use Sujip\PayPal\Notification\Payload;

final readonly class Arrayable implements PayloadContract
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        private array $payload = [],
    ) {
    }

    public function create(): Payload
    {
        return new Payload($this->payload);
    }
}
