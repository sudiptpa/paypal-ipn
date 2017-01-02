<?php

namespace PayPal\IPN\Listener\Http;

use PayPal\IPN\Listener\HttpListener;
use PayPal\IPN\Message\ArrayMessage;

class ArrayListener extends HttpListener
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    protected function getMessage()
    {
        return new ArrayMessage($this->data);
    }
}
