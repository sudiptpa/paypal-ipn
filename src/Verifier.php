<?php

namespace PayPal\IPN;

class Verifier
{
    const STATUS_KEYWORD_INVALID = 'INVALID';

    const STATUS_KEYWORD_VERIFIED = 'VERIFIED';

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
     * @param IPNMessage $message
     *
     * @return mixed
     */
    public function verify(IPNMessage $message)
    {
        $serviceResponse = $this->service->verifyIpnMessage($message);
        $serviceResponseBody = $serviceResponse->getBody();

        $pattern = sprintf('/(%s|%s)/', self::STATUS_KEYWORD_VERIFIED, self::STATUS_KEYWORD_INVALID);

        if (!preg_match($pattern, $serviceResponseBody)) {
            throw new \UnexpectedValueException(sprintf('Unexpected verification status encountered: %s', $serviceResponseBody));
        }

        return $serviceResponseBody === self::STATUS_KEYWORD_VERIFIED;
    }
}
