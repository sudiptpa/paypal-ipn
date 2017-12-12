<?php

namespace Sujip\PayPal\Notification\Events;

use Sujip\PayPal\Notification\Payload;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class Verification
 * @package Sujip\PayPal\Notification\Events
 */
abstract class Verification extends Event
{
    /**
     * @var mixed
     */
    private $payload;

    /**
     * @param Payload $payload
     */
    public function __construct(Payload $payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }
}
