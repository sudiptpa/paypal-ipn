<?php

namespace PayPal\IPN;

interface Service
{
    /**
     * @param IPNMessage $message
     */
    public function verifyIpnMessage(IPNMessage $message);
}
