<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification\Tests;

use PHPUnit\Framework\TestCase;
use Sujip\PayPal\Notification\Handler\ArrayHandler;
use Sujip\PayPal\Notification\Ipn;

final class EndpointTest extends TestCase
{
    private const SANDBOX = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';

    private const LIVE = 'https://ipnpb.paypal.com/cgi-bin/webscr';

    public function testEnvDefaultsToLive(): void
    {
        $handler = new ArrayHandler(['foo' => 'bar']);

        $this->assertFalse($handler->env());
        $this->assertSame(self::LIVE, $handler->url());
    }

    public function testEnvChangesWhenSandboxIsEnabled(): void
    {
        $handler = new ArrayHandler(['foo' => 'bar']);

        $this->assertTrue($handler->sandbox()->env());
        $this->assertSame(self::SANDBOX, $handler->url());
    }

    public function testUrlSwitchesBetweenLiveAndSandbox(): void
    {
        $handler = new ArrayHandler(['foo' => 'bar']);

        $this->assertSame(self::LIVE, $handler->url());
        $this->assertSame(self::SANDBOX, $handler->sandbox()->url());
        $this->assertSame(self::LIVE, $handler->live()->url());
    }

    public function testModernFacadeUsesSameEndpointSwitching(): void
    {
        $ipn = Ipn::fromArray(['foo' => 'bar']);

        $this->assertSame(self::LIVE, $ipn->url());
        $this->assertSame(self::SANDBOX, $ipn->sandbox()->url());
        $this->assertSame(self::LIVE, $ipn->live()->url());
    }
}
