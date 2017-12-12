<?php

namespace Sujip\PayPal\Notification\Handler;

use Sujip\PayPal\Notification\Handler;
use Sujip\PayPal\Notification\Payload\Arrayable;

/**
 * Class ArrayHandler
 * @package Sujip\PayPal\Notification\Handler
 */
class ArrayHandler extends Handler
{
    /**
     * @var array
     */
    protected $payload = [];

    /**
     * @param array $payload
     */
    public function __construct(array $payload = [])
    {
        $this->payload = $payload;
    }

    /**
     * @param array $payload
     */
    public function setPayload(array $payload)
    {
        $this->payload = $payload;
    }

    protected function getPayload()
    {
        return new Arrayable($this->payload);
    }
}
