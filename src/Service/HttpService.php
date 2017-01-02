<?php

namespace PayPal\IPN\Service;

use Exception;
use GuzzleHttp\ClientInterface;
use PayPal\IPN\Exception\ServiceException;
use PayPal\IPN\IPNMessage;
use PayPal\IPN\Service;
use PayPal\IPN\ServiceResponse;

class HttpService implements Service
{
    /**
     * @var mixed
     */
    private $httpClient;

    /**
     * @var mixed
     */
    private $serviceEndpoint;

    /**
     * @param ClientInterface $httpClient
     * @param $serviceEndpoint
     */
    public function __construct(ClientInterface $httpClient, $serviceEndpoint)
    {
        $this->httpClient = $httpClient;
        $this->serviceEndpoint = $serviceEndpoint;
    }

    /**
     * @param IPNMessage $message
     */
    public function verifyIpnMessage(IPNMessage $message)
    {
        $requestBody = array_merge(
            ['cmd' => '_notify-validate'],
            $message->getAll()
        );

        try {
            $response = $this->httpClient->post(
                $this->serviceEndpoint,
                ['form_params' => $requestBody]
            );
        } catch (Exception $e) {
            throw new ServiceException($e->getMessage());
        }

        return new ServiceResponse(
            (string) $response->getBody()
        );
    }
}
