<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Http;

class Verifier extends IpnVerifier
{
    public const IPN_INVALID = self::STATUS_INVALID;

    public const IPN_VERIFIED = self::STATUS_VERIFIED;
}
