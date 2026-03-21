<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Http;

class VerificationResponse
{
    public function __construct(
        private readonly string $body,
        private readonly int $statusCode = 200,
    ) {
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function isVerified(): bool
    {
        return trim($this->body) === IpnVerifier::STATUS_VERIFIED;
    }

    public function isInvalid(): bool
    {
        return trim($this->body) === IpnVerifier::STATUS_INVALID;
    }

    public function getCode(): int
    {
        return $this->statusCode;
    }
}
