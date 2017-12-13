<?php

namespace Sujip\PayPal\Notification\Payload;

use Sujip\PayPal\Notification\Contracts\Payload as PayloadContract;
use Sujip\PayPal\Notification\Payload;

/**
 * Class Arrayable.
 *
 * @package Sujip\PayPal\Notification\Payload
 */
class Arrayable implements PayloadContract
{
    /**
     * @var array
     */
    private $payload;

    /**
     * @param array $payload
     */
    public function __construct(array $payload = [])
    {
        $this->payload = $payload;
    }

    public function create()
    {
        return new Payload($this->payload);
    }
}
