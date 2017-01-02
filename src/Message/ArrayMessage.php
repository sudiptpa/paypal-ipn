<?php

namespace PayPal\IPN\Message;

use PayPal\IPN\IPNMessage;
use PayPal\IPN\Message;

class ArrayMessage implements Message
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function createMessage()
    {
        return new IPNMessage($this->data);
    }
}
