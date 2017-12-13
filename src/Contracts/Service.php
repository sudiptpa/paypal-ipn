<?php

namespace Sujip\PayPal\Notification\Contracts;

use Sujip\PayPal\Notification\Payload;

interface Service
{
    /**
     * @param Payload $payload
     */
    public function call(Payload $payload);
}
