<?php

namespace Sujip\PayPal\Notification;

use PayPal\IPN\Exception\ServiceException;
use Sujip\PayPal\Notification\Contracts\Payload;
use Sujip\PayPal\Notification\Events\Invalid;
use Sujip\PayPal\Notification\Events\Verified;
use Sujip\PayPal\Notification\Http\Verifier;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcher;

/**
 * Class Manager.
 *
 * @package Sujip\PayPal\Notification
 */
class Manager
{
    const IPN_INVALID_EVENT = 'ipn:invalid';
    const IPN_FAILURE_EVENT = 'ipn:verification.failure';
    const IPN_VERIFIED_EVENT = 'ipn:verified';

    /**
     * @var mixed
     */
    private $dispatcher;

    /**
     * @var mixed
     */
    private $payload;

    /**
     * @var mixed
     */
    private $verifier;

    /**
     * @param Payload         $payload
     * @param Verifier        $verifier
     * @param EventDispatcher $dispatcher
     */
    public function __construct(Payload $payload, Verifier $verifier, EventDispatcher $dispatcher)
    {
        $this->payload = $payload;
        $this->verifier = $verifier;
        $this->eventDispatcher = $dispatcher;
    }

    public function subscribe()
    {
        $payload = $this->payload->create();

        try {
            $response = $this->verifier->verify($payload);

            if ($response->isVerified()) {
                $name = self::IPN_VERIFIED_EVENT;
                $event = new Verified($payload);
            }

            if ($response->isInvalid()) {
                $name = self::IPN_INVALID_EVENT;
                $event = new Invalid($payload);
            }
        } catch (\UnexpectedValueException $e) {
            $name = self::IPN_FAILURE_EVENT;
            $event = new Failure(
                $payload,
                $e->getMessage()
            );
        } catch (ServiceException $e) {
            $name = self::IPN_FAILURE_EVENT;
            $event = new Failure(
                $payload,
                $e->getMessage()
            );
        }

        $this->eventDispatcher->dispatch($name, $event);
    }

    /**
     * @param callable $listener
     */
    public function onInvalid(callable $listener)
    {
        $this->eventDispatcher->addListener(self::IPN_INVALID_EVENT, $listener);
    }

    /**
     * @param callable $listener
     */
    public function onError(callable $listener)
    {
        $this->eventDispatcher->addListener(self::IPN_FAILURE_EVENT, $listener);
    }

    /**
     * @param callable $listener
     */
    public function onVerified(callable $listener)
    {
        $this->eventDispatcher->addListener(self::IPN_VERIFIED_EVENT, $listener);
    }
}
