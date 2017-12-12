<?php

namespace Sujip\PayPal\Notification\Payload;

use Sujip\PayPal\Notification\Contracts\Payload as Contract;
use Sujip\PayPal\Notification\Payload;

/**
 * Class Streamable
 * @package Sujip\PayPal\Notification\Payload
 */
class Streamable implements Contract
{
    public function create()
    {
        return new Payload(
            $this->getStream()
        );
    }

    public function getStream()
    {
        return file_get_contents('php://input');
    }
}
