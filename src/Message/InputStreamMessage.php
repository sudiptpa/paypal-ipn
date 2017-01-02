<?php

namespace PayPal\IPN\Message;

use PayPal\IPN\InputStream;
use PayPal\IPN\IPNMessage;
use PayPal\IPN\Message;

class InputStreamMessage implements Message
{
    /**
     * @var mixed
     */
    private $inputStream;

    /**
     * @param InputStream $inputStream
     */
    public function __construct(InputStream $inputStream)
    {
        $this->inputStream = $inputStream;
    }

    public function createMessage()
    {
        $streamContents = $this->inputStream->getContents();

        return new IPNMessage($streamContents);
    }
}
