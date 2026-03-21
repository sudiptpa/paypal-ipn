<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Http;

use Sujip\PayPal\Notification\Contracts\Service;
use Sujip\PayPal\Notification\Exceptions\ServiceException;
use Sujip\PayPal\Notification\Payload;

class CurlTransport implements Service
{
    public function __construct(
        private readonly string $url = 'https://ipnpb.paypal.com/cgi-bin/webscr',
        private readonly int $timeoutSeconds = 30,
    ) {
    }

    public function call(Payload $payload): Response
    {
        if (!function_exists('curl_init')) {
            throw new ServiceException(
                'The built-in cURL transport is unavailable because the cURL extension is not installed.'
            );
        }

        $body = http_build_query(array_merge(['cmd' => '_notify-validate'], $payload->all()), '', '&');
        $handle = curl_init($this->url);

        if ($handle === false) {
            throw new ServiceException('Unable to initialize the cURL transport.');
        }

        curl_setopt_array($handle, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_TIMEOUT => $this->timeoutSeconds,
            CURLOPT_CONNECTTIMEOUT => $this->timeoutSeconds,
        ]);

        $rawResponse = curl_exec($handle);

        if (!is_string($rawResponse)) {
            $message = curl_error($handle) ?: 'Unknown cURL error.';
            curl_close($handle);

            throw new ServiceException($message);
        }

        $statusCode = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        $headerSize = (int) curl_getinfo($handle, CURLINFO_HEADER_SIZE);
        $responseBody = substr($rawResponse, $headerSize);
        curl_close($handle);

        return new Response($responseBody, $statusCode);
    }
}
