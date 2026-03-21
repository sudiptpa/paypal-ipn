<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Events;

use Sujip\PayPal\Notification\Payload;

final class Invalid extends Verification
{
    public function __construct(
        Payload $payload,
        private readonly ?string $error = null,
    ) {
        parent::__construct($payload);
    }

    public function error(): ?string
    {
        return $this->error;
    }
}
