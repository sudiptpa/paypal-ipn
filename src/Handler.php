<?php

namespace Sujip\PayPal\Notification;

use GuzzleHttp\Client;
use Sujip\PayPal\Notification\Http\Endpoint;
use Sujip\PayPal\Notification\Http\Request;
use Sujip\PayPal\Notification\Manager;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class Handler
 * @package Sujip\PayPal\Notification
 */
abstract class Handler
{
    use Endpoint;

    public function run()
    {
        return new Manager(
            $this->getPayload(),
            $this->getVerifier(),
            new EventDispatcher
        );
    }

    /**
     * @return Message
     */
    abstract protected function getPayload();

    /**
     * @return Verifier
     */
    private function getVerifier()
    {
        $service = new Request(new Client(), $this->url());

        return new Verifier($service);
    }
}
