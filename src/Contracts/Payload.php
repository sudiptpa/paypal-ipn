<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Contracts;

use Sujip\PayPal\Notification\Payload as PayPalPayload;

interface Payload
{
    public function create(): PayPalPayload;
}
