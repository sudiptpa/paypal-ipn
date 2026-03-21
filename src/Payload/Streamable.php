<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Payload;

use Sujip\PayPal\Notification\Contracts\Payload as PayloadContract;
use Sujip\PayPal\Notification\Payload;

final class Streamable implements PayloadContract
{
    public function create(): Payload
    {
        return new Payload($this->getStream());
    }

    public function getStream(): string
    {
        return file_get_contents('php://input') ?: '';
    }
}
