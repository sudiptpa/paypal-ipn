<?php

namespace Sujip\PayPal\Notification\Http;

trait Endpoint
{
    /**
     * @var bool
     */
    private $sandbox = false;

    public function sandbox()
    {
        $this->sandbox = true;
    }

    /**
     * @return string
     */
    protected function url()
    {
        return $this->sandbox ?
            'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr' :
            'https://ipnpb.paypal.com/cgi-bin/webscr';
    }
}
