<?php

namespace Sujip\PayPal\Notification\Handler;

use Sujip\PayPal\Notification\Handler;

/**
 * Class StreamHandler.
 *
 * @package Sujip\PayPal\Notification\Handler
 */
class StreamHandler extends Handler
{
    protected function getPayload()
    {
        return new Streamable();
    }
}
