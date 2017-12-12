<?php

namespace Sujip\PayPal\Notification\Http;

use GuzzleHttp\Message\Response as GuzzleResponse;

/**
 * Class Response.
 *
 * @package Sujip\PayPal\Notification\Http
 */
class Response extends GuzzleResponse
{
    /**
     * The guzzle http client response.
     *
     * @var \GuzzleHttp\Message\Response
     */
    protected $response;

    /**
     * Create a new response instance.
     *
     * @param GuzzleHttpResponse $response
     */
    public function __construct(GuzzleHttpResponse $response)
    {
        $this->response = $response;
    }

    public function getBody()
    {
        return (string) $this->response->getBody();
    }

    /**
     * @return mixed
     */
    public function isVerified()
    {
        return $this->getBody() === Verifier::PAYPAL_VERIFIED;
    }

    /**
     * @return mixed
     */
    public function isInvalid()
    {
        return $this->getBody() === Verifier::PAYPAL_INVALID;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->response->getStatusCode();
    }
}
