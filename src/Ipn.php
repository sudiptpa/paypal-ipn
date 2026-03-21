<?php

declare(strict_types=1);

namespace Sujip\PayPal\Notification;

use Sujip\PayPal\Notification\Contracts\Service;
use Sujip\PayPal\Notification\Events\Verification;
use Sujip\PayPal\Notification\Handler\ArrayHandler;
use Sujip\PayPal\Notification\Handler\StreamHandler;
use Sujip\PayPal\Notification\Http\Endpoint;

final class Ipn
{
    use Endpoint;

    private const SOURCE_ARRAY = 'array';

    private const SOURCE_RAW = 'raw';

    private const SOURCE_STREAM = 'stream';

    private ?Service $service = null;

    private mixed $transport = null;

    private ?object $dispatcher = null;

    private int $timeoutSeconds = 30;

    /** @var list<callable> */
    private array $verifiedListeners = [];

    /** @var list<callable> */
    private array $invalidListeners = [];

    /** @var list<callable> */
    private array $errorListeners = [];

    /**
     * @param self::SOURCE_ARRAY|self::SOURCE_RAW|self::SOURCE_STREAM $source
     * @param array<string, mixed>|string|null $payload
     */
    private function __construct(
        private readonly string $source,
        private readonly array|string|null $payload = null,
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(self::SOURCE_ARRAY, $payload);
    }

    public static function fromRaw(string $payload): self
    {
        return new self(self::SOURCE_RAW, $payload);
    }

    public static function fromStream(): self
    {
        return new self(self::SOURCE_STREAM);
    }

    public static function fromGlobals(): self
    {
        /** @var array<string, mixed> $payload */
        $payload = $_POST;

        return new self(self::SOURCE_ARRAY, $payload);
    }

    public function using(Service $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function withTransport(mixed $transport): static
    {
        $this->transport = $transport;

        return $this;
    }

    public function withClient(mixed $transport): static
    {
        return $this->withTransport($transport);
    }

    public function withTimeout(int $timeoutSeconds): static
    {
        $this->timeoutSeconds = $timeoutSeconds;

        return $this;
    }

    public function withDispatcher(object $dispatcher): static
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

    public function onVerified(callable $listener): static
    {
        $this->verifiedListeners[] = $listener;

        return $this;
    }

    public function onInvalid(callable $listener): static
    {
        $this->invalidListeners[] = $listener;

        return $this;
    }

    public function onError(callable $listener): static
    {
        $this->errorListeners[] = $listener;

        return $this;
    }

    public function handle(): Manager
    {
        return $this->manager();
    }

    public function manager(): Manager
    {
        $manager = $this->buildHandler()->handle();

        foreach ($this->verifiedListeners as $listener) {
            $manager->onVerified($listener);
        }

        foreach ($this->invalidListeners as $listener) {
            $manager->onInvalid($listener);
        }

        foreach ($this->errorListeners as $listener) {
            $manager->onError($listener);
        }

        return $manager;
    }

    public function verify(): Verification
    {
        return $this->manager()->fire();
    }

    private function buildHandler(): Handler
    {
        $handler = match ($this->source) {
            self::SOURCE_ARRAY => new ArrayHandler(is_array($this->payload) ? $this->payload : []),
            self::SOURCE_RAW => new ArrayHandler((new Payload(is_string($this->payload) ? $this->payload : ''))->all()),
            self::SOURCE_STREAM => new StreamHandler(),
        };

        if ($this->env()) {
            $handler->sandbox();
        } else {
            $handler->live();
        }

        if ($this->service instanceof Service) {
            $handler->using($this->service);
        }

        if ($this->transport !== null) {
            $handler->withTransport($this->transport);
        }

        if ($this->dispatcher !== null) {
            $handler->withDispatcher($this->dispatcher);
        }

        return $handler->withTimeout($this->timeoutSeconds);
    }
}
