<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification;

use Sujip\PayPal\Notification\Contracts\DispatcherInterface;
use Sujip\PayPal\Notification\Contracts\Payload as PayloadContract;
use Sujip\PayPal\Notification\Events\Failure;
use Sujip\PayPal\Notification\Events\Invalid;
use Sujip\PayPal\Notification\Events\Verification;
use Sujip\PayPal\Notification\Events\Verified;
use Sujip\PayPal\Notification\Exceptions\ServiceException;
use Sujip\PayPal\Notification\Http\IpnVerifier;

final readonly class Manager
{
    public const IPN_INVALID = 'ipn:invalid';

    public const IPN_FAILURE = 'ipn:verification.failure';

    public const IPN_VERIFIED = 'ipn:verified';

    public function __construct(
        private PayloadContract $payload,
        private IpnVerifier $verifier,
        private DispatcherInterface $eventDispatcher,
    ) {
    }

    public function fire(): Verification
    {
        $payload = $this->payload->create();

        try {
            $response = $this->verifier->verify($payload);
            $event = $response->isVerified()
                ? new Verified($payload)
                : new Invalid($payload);
            $name = $response->isVerified() ? self::IPN_VERIFIED : self::IPN_INVALID;
        } catch (\UnexpectedValueException|ServiceException $exception) {
            $name = self::IPN_FAILURE;
            $event = new Failure($payload, $exception->getMessage());
        }

        $this->eventDispatcher->dispatch($event, $name);

        return $event;
    }

    public function onInvalid(callable $callback): void
    {
        $this->eventDispatcher->addListener(self::IPN_INVALID, $callback);
    }

    public function onError(callable $callback): void
    {
        $this->eventDispatcher->addListener(self::IPN_FAILURE, $callback);
    }

    public function onVerified(callable $callback): void
    {
        $this->eventDispatcher->addListener(self::IPN_VERIFIED, $callback);
    }
}
