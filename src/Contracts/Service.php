<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Contracts;

use Sujip\PayPal\Notification\Http\Response;
use Sujip\PayPal\Notification\Payload;

interface Service
{
    public function call(Payload $payload): Response;
}
