<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Http;

trait Endpoint
{
    private bool $sandbox = false;

    public function sandbox(): static
    {
        $this->sandbox = true;

        return $this;
    }

    public function live(): static
    {
        $this->sandbox = false;

        return $this;
    }

    public function env(): bool
    {
        return $this->sandbox;
    }

    public function url(): string
    {
        return $this->sandbox
            ? 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr'
            : 'https://ipnpb.paypal.com/cgi-bin/webscr';
    }
}
