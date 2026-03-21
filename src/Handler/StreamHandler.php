<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Handler;

use Sujip\PayPal\Notification\Handler;
use Sujip\PayPal\Notification\Payload\Streamable;

class StreamHandler extends Handler
{
    protected function getPayload(): Streamable
    {
        return new Streamable();
    }
}
