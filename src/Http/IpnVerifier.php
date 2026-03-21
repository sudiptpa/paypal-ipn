<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Http;

use Sujip\PayPal\Notification\Contracts\Service;
use Sujip\PayPal\Notification\Payload;
use UnexpectedValueException;

class IpnVerifier
{
    public const STATUS_INVALID = 'INVALID';

    public const STATUS_VERIFIED = 'VERIFIED';

    public function __construct(
        private readonly Service $service,
    ) {
    }

    public function verify(Payload $payload): Response
    {
        $response = $this->service->call($payload);
        $status = trim($response->getBody());

        if (!in_array($status, [self::STATUS_VERIFIED, self::STATUS_INVALID], true)) {
            throw new UnexpectedValueException(
                sprintf('Unexpected verification status encountered: %s', $status)
            );
        }

        return $response;
    }
}
