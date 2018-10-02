<?php

namespace Sujip\PayPal\Notification\Http;

use GuzzleHttp\Psr7\Response as Psr7Response;

/**
 * Class Response.
 *
 * @package Sujip\PayPal\Notification\Http
 */
class Response extends Psr7Response
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
     * @param Psr7Response $response
     */
    public function __construct(Psr7Response $response)
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
        return $this->getBody() === Verifier::IPN_VERIFIED;
    }

    /**
     * @return mixed
     */
    public function isInvalid()
    {
        return $this->getBody() === Verifier::IPN_INVALID;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->response->getStatusCode();
    }
}
