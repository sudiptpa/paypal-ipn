<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Http;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Sujip\PayPal\Notification\Contracts\Service;
use Sujip\PayPal\Notification\Exceptions\ServiceException;
use Sujip\PayPal\Notification\Payload;

class GuzzleTransport implements Service
{
    public function __construct(
        private readonly ClientInterface $client = new Client(),
        private readonly string $url = 'https://ipnpb.paypal.com/cgi-bin/webscr',
        private readonly int $timeoutSeconds = 30,
    ) {
    }

    public function call(Payload $payload): Response
    {
        $body = array_merge(
            ['cmd' => '_notify-validate'],
            $payload->all(),
        );

        try {
            $response = $this->client->request('POST', $this->url, [
                'form_params' => $body,
                'timeout' => $this->timeoutSeconds,
                'http_errors' => false,
            ]);
        } catch (GuzzleException $guzzleException) {
            throw new ServiceException(
                $guzzleException->getMessage(),
                (int) $guzzleException->getCode(),
                $guzzleException,
            );
        }

        return new Response((string) $response->getBody(), $response->getStatusCode());
    }
}
