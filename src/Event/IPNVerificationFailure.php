<?php

namespace PayPal\IPN\Event;

use PayPal\IPN\IPNMessage;

class IPNVerificationFailure extends IPNVerification
{
    /**
     * @var mixed
     */
    private $error;

    /**
     * @param IPNMessage $message
     * @param $error
     */
    public function __construct(IPNMessage $message, $error)
    {
        $this->error = $error;

        parent::__construct($message);
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
}
