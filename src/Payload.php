<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification;

final class Payload implements \Stringable
{
    /** @var array<array-key, mixed> */
    private array $payload = [];

    /**
     * @param array<array-key, mixed>|string $payload
     */
    public function __construct(array|string $payload)
    {
        $this->payload = is_array($payload)
            ? $payload
            : $this->parseRawString($payload);
    }

    public function __toString(): string
    {
        return http_build_query($this->payload, '', '&');
    }

    public function find(string $key): mixed
    {
        return $this->payload[$key] ?? null;
    }

    /**
     * @return array<array-key, mixed>
     */
    public function all(): array
    {
        return $this->payload;
    }

    /**
     * @return array<array-key, mixed>
     */
    private function parseRawString(string $string): array
    {
        $data = [];
        parse_str($string, $data);

        return is_array($data) ? $data : [];
    }
}
