<?php

namespace PayPal\IPN;

interface Message
{
    /**
     * @return Message
     */
    public function createMessage();
}
