<?php

namespace PayPal\IPN;

use PayPal\IPN\Event\IPNInvalid;
use PayPal\IPN\Event\IPNVerificationFailure;
use PayPal\IPN\Event\IPNVerified;
use PayPal\IPN\Exception\ServiceException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcher;

class IPNListener
{
    const IPN_INVALID_EVENT = 'ipn.message.invalid';

    const IPN_VERIFICATION_FAILURE_EVENT = 'ipn.message.verification_failure';

    const IPN_VERIFIED_EVENT = 'ipn.message.verified';

    /**
     * @var mixed
     */
    private $eventDispatcher;

    /**
     * @var mixed
     */
    private $message;

    /**
     * @var mixed
     */
    private $verifier;

    /**
     * @param Message         $message
     * @param Verifier        $verifier
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(Message $message, Verifier $verifier, EventDispatcher $eventDispatcher)
    {
        $this->message = $message;
        $this->verifier = $verifier;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function listen()
    {
        $message = $this->message->createMessage();

        try {
            $result = $this->verifier->verify($message);

            if ($result) {
                $eventName = self::IPN_VERIFIED_EVENT;
                $event = new IPNVerified($message);
            } else {
                $eventName = self::IPN_INVALID_EVENT;
                $event = new IPNInvalid($message);
            }
        } catch (\UnexpectedValueException $e) {
            $eventName = self::IPN_VERIFICATION_FAILURE_EVENT;
            $event = new IPNVerificationFailure($message, $e->getMessage());
        } catch (ServiceException $e) {
            $eventName = self::IPN_VERIFICATION_FAILURE_EVENT;
            $event = new IPNVerificationFailure($message, $e->getMessage());
        }

        $this->eventDispatcher->dispatch($eventName, $event);
    }

    /**
     * @param callable $listener
     */
    public function onInvalid($listener)
    {
        $this->eventDispatcher->addListener(self::IPN_INVALID_EVENT, $listener);
    }

    /**
     * @param callable $listener
     */
    public function onVerificationFailure($listener)
    {
        $this->eventDispatcher->addListener(self::IPN_VERIFICATION_FAILURE_EVENT, $listener);
    }

    /**
     * @param callable $listener
     */
    public function onVerified($listener)
    {
        $this->eventDispatcher->addListener(self::IPN_VERIFIED_EVENT, $listener);
    }
}
