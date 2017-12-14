<?php

namespace Sujip\PayPal\Notification\Http;

trait Endpoint
{
    /**
     * @var bool
     */
    private $sandbox = false;

    /**
     * @return mixed
     */
    public function sandbox()
    {
        $this->sandbox = true;

        return $this;
    }

    /**
     * @return string
     */
    public function env()
    {
        return $this->sandbox;
    }

    /**
     * @return string
     */
    public function url()
    {
        return $this->sandbox ?
            'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr' :
            'https://ipnpb.paypal.com/cgi-bin/webscr';
    }
}
