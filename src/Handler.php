<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification;

use Sujip\PayPal\Notification\Contracts\DispatcherInterface;
use Sujip\PayPal\Notification\Contracts\Payload as PayloadContract;
use Sujip\PayPal\Notification\Contracts\Service;
use Sujip\PayPal\Notification\EventDispatcher\ExternalDispatcherAdapter;
use Sujip\PayPal\Notification\EventDispatcher\ListenerDispatcher;
use Sujip\PayPal\Notification\Http\Endpoint;
use Sujip\PayPal\Notification\Http\IpnVerifier;
use Sujip\PayPal\Notification\Http\VerificationTransport;

abstract class Handler
{
    use Endpoint;

    private ?Service $service = null;

    private ?DispatcherInterface $dispatcher = null;

    private mixed $transport = null;

    private int $timeoutSeconds = 30;

    public function using(Service $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function withTransport(mixed $transport): static
    {
        $this->transport = $transport;

        return $this;
    }

    public function withClient(mixed $transport): static
    {
        return $this->withTransport($transport);
    }

    public function withTimeout(int $timeoutSeconds): static
    {
        $this->timeoutSeconds = $timeoutSeconds;

        return $this;
    }

    public function withDispatcher(object $dispatcher): static
    {
        $this->dispatcher = $dispatcher instanceof DispatcherInterface
            ? $dispatcher
            : new ExternalDispatcherAdapter($dispatcher);

        return $this;
    }

    public function handle(): Manager
    {
        return new Manager(
            $this->getPayload(),
            $this->getVerifier(),
            $this->dispatcher ?? new ListenerDispatcher(),
        );
    }

    abstract protected function getPayload(): PayloadContract;

    private function getVerifier(): IpnVerifier
    {
        return new IpnVerifier(
            $this->service ?? new VerificationTransport($this->transport, $this->url(), $this->timeoutSeconds),
        );
    }
}
