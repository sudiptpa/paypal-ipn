<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Http;

use InvalidArgumentException;
use Sujip\PayPal\Notification\Contracts\Service;
use Sujip\PayPal\Notification\Exceptions\ServiceException;
use Sujip\PayPal\Notification\Payload;

class VerificationTransport implements Service
{
    private ?Service $service;

    public function __construct(
        mixed $transport = null,
        private readonly string $url = 'https://ipnpb.paypal.com/cgi-bin/webscr',
        private readonly int $timeoutSeconds = 30,
    ) {
        if ($transport !== null && !$transport instanceof Service && !$this->isGuzzleClient($transport)) {
            throw new InvalidArgumentException(
                'Request transport must be null, a Service implementation, or a Guzzle client instance.'
            );
        }

        $this->service = $transport instanceof Service
            ? $transport
            : ($this->isGuzzleClient($transport) ? new GuzzleTransport($transport, $this->url, $this->timeoutSeconds) : null);
    }

    public function call(Payload $payload): Response
    {
        return $this->resolveService()->call($payload);
    }

    private function resolveService(): Service
    {
        if ($this->service instanceof Service) {
            return $this->service;
        }

        if (function_exists('curl_init')) {
            $this->service = new CurlTransport($this->url, $this->timeoutSeconds);

            return $this->service;
        }

        if (class_exists('GuzzleHttp\\Client')) {
            $this->service = new GuzzleTransport(null, $this->url, $this->timeoutSeconds);

            return $this->service;
        }

        throw new ServiceException(
            'No default HTTP transport is available. Install ext-curl, install guzzlehttp/guzzle, or inject a custom transport.'
        );
    }

    private function isGuzzleClient(mixed $transport): bool
    {
        return interface_exists('GuzzleHttp\\ClientInterface')
            && is_object($transport)
            && $transport instanceof \GuzzleHttp\ClientInterface;
    }
}
