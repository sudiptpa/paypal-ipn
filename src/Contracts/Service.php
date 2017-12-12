<?php

namespace Sujip\PayPal\Notification\Contracts;

interface Service
{
    /**
     * @param Payload $payload
     */
    public function verifyPayload(Payload $payload);
}
