<?php

namespace Sujip\PayPal\Notification\Http;

use Sujip\PayPal\Notification\Contracts\Payload;
use Sujip\PayPal\Notification\Contracts\Service;

/**
 * Class Verifier
 * @package Sujip\PayPal\Notification\Http
 */
class Verifier
{
    const PAYPAL_INVALID = 'INVALID';
    const PAYPAL_VERIFIED = 'VERIFIED';

    /**
     * @var mixed
     */
    private $service;

    /**
     * @param Service $service
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     *  Send HTTP Request to PayPal & verfify payload.
     *
     * @param Payload $payload
     * @return mixed
     */
    public function verify(Payload $payload)
    {
        $response = $this->service->verifyPayload($payload);

        $string = $response->getBody();

        $pattern = sprintf('/(%s|%s)/', self::PAYPAL_VERIFIED, self::PAYPAL_INVALID);

        if (!preg_match($pattern, $string)) {
            throw new \UnexpectedValueException(
                sprintf('Unexpected verification status encountered: %s', $string)
            );
        }

        return $response;
    }
}
