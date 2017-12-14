<?php

namespace Sujip\Paypal\Notification\Test;

use GuzzleHttp\Client as GuzzleClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Sujip\PayPal\Notification\Handler\ArrayHandler;

/**
 * Class ArrayHandlerTest
 * @package Sujip\Paypal\Notification\Test
 */
class ArrayHandlerTest extends TestCase
{
    public function setUp()
    {
        $this->event = (new ArrayHandler([
            'foo' => 'bar',
            'bar' => 'baz',
        ]));
    }

    /** @test */
    public function testInstanceOf()
    {
        $this->assertInstanceOf(ArrayHandler::class, $this->event);
    }

    /**
     * @param $response
     * @param $endpoint
     * @param $parameters
     * @return mixed
     */
    private function mockGuzzleRequest($response, $endpoint, $parameters)
    {
        $mockResponse = $this->getMockBuilder(ResponseInterface::class)
            ->getMock();
        $mockResponse->expects($this->once())
            ->method('getBody')
            ->willReturn($response);

        $mockGuzzle = $this->getMockBuilder(GuzzleClient::class)
            ->setMethods(['post'])
            ->getMock();
        $mockGuzzle->expects($this->once())
            ->method('post')
            ->with($endpoint, $parameters)
            ->willReturn($mockResponse);

        return $mockGuzzle;
    }
}
