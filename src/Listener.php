<?php

namespace PayPal\IPN;

use Symfony\Component\EventDispatcher\EventDispatcher;

abstract class Listener
{
    public function run()
    {
        $verifier = $this->getVerifier();
        $message = $this->getMessage();
        $eventDispatcher = $this->getEventDispatcher();

        return new IPNListener(
            $message,
            $verifier,
            $eventDispatcher
        );
    }

    /**
     * @return Message
     */
    abstract protected function getMessage();

    /**
     * @return Service
     */
    abstract protected function getService();

    /**
     * @return EventDispatcher
     */
    private function getEventDispatcher()
    {
        return new EventDispatcher();
    }

    /**
     * @return Verifier
     */
    private function getVerifier()
    {
        $service = $this->getService();

        return new Verifier($service);
    }
}
