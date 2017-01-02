<?php

namespace PayPal\IPN\Listener;

use GuzzleHttp\Client;
use PayPal\IPN\Listener;
use PayPal\IPN\Service\HttpService;

abstract class HttpListener extends Listener
{
    use ServiceEndpoint;

    protected function getService()
    {
        return new HttpService(
            new Client(),
            $this->getServiceEndpoint()
        );
    }
}
