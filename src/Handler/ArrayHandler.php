<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Handler;

use Sujip\PayPal\Notification\Handler;
use Sujip\PayPal\Notification\Payload\Arrayable;

class ArrayHandler extends Handler
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(protected array $payload = [])
    {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }

    public function getPayload(): Arrayable
    {
        return new Arrayable($this->payload);
    }
}
