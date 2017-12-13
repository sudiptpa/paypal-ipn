<?php

namespace Sujip\PayPal\Notification\Http;

use Sujip\PayPal\Notification\Contracts\Service;
use Sujip\PayPal\Notification\Payload;
use UnexpectedValueException;

/**
 * Class Verifier.
 *
 * @package Sujip\PayPal\Notification\Http
 */
class Verifier
{
    const IPN_INVALID = 'INVALID';
    const IPN_VERIFIED = 'VERIFIED';

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
     *
     * @return mixed
     */
    public function verify(Payload $payload)
    {
        $response = $this->service->call($payload);

        $string = $response->getBody();

        $pattern = sprintf('/(%s|%s)/', self::IPN_VERIFIED, self::IPN_INVALID);

        if (!preg_match($pattern, $string)) {
            throw new UnexpectedValueException(
                sprintf('Unexpected verification status encountered: %s', $string)
            );
        }

        return $response;
    }
}
