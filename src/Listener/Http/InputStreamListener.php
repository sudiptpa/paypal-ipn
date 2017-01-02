<?php

namespace PayPal\IPN\Listener\Http;

use PayPal\IPN\InputStream;
use PayPal\IPN\Listener\HttpListener;
use PayPal\IPN\Message\InputStreamMessage;

class InputStreamListener extends HttpListener
{
    protected function getMessage()
    {
        return new InputStreamMessage(new InputStream());
    }
}
