# PayPal IPN

Framework-agnostic, modernized PayPal IPN verification package for legacy Instant Payment Notification workflows.

[![CI](https://github.com/sudiptpa/paypal-ipn/actions/workflows/ci.yml/badge.svg)](https://github.com/sudiptpa/paypal-ipn/actions/workflows/ci.yml)
[![Packagist Version](https://img.shields.io/packagist/v/sudiptpa/paypal-ipn.svg)](https://packagist.org/packages/sudiptpa/paypal-ipn)
[![Packagist Downloads](https://img.shields.io/packagist/dt/sudiptpa/paypal-ipn.svg)](https://packagist.org/packages/sudiptpa/paypal-ipn)
[![PHP Version](https://img.shields.io/badge/php-8.2--8.5-777bb4.svg)](https://www.php.net/)
[![License](https://img.shields.io/packagist/l/sudiptpa/paypal-ipn.svg)](LICENSE)

## Why This Package

PayPal IPN is legacy, but thousands of projects still depend on it. This package keeps the familiar listener flow that existing integrations already use while modernizing the internals for current PHP versions and optional transport strategies.

## Highlights

- stable legacy-style usage with `ArrayHandler` and `StreamHandler`
- modern fluent usage with `Sujip\PayPal\Notification\Ipn`
- zero hard runtime dependencies beyond PHP
- no hard Guzzle dependency
- no hard Symfony dependency
- built-in lightweight event dispatcher
- built-in cURL transport when `ext-curl` is available
- optional Guzzle transport support
- custom transport and custom dispatcher support
- PHP `8.2` to `<8.6`

## Installation

```bash
composer require sudiptpa/paypal-ipn
```

Optional Guzzle usage:

```bash
composer require guzzlehttp/guzzle
```

## Documentation

- [User Guide](docs/user-guide.md)
- [Migration Guide](docs/migration-guide.md)
- [Architecture](ARCHITECTURE.md)
- [cURL Transport](docs/transports/curl.md)
- [Guzzle Transport](docs/transports/guzzle.md)
- [Custom Transport](docs/transports/custom.md)
- [Contributing](CONTRIBUTING.md)
- [Security](SECURITY.md)

## Looking For A Modern Unified PayPal Package?

If you are starting a new integration or want one modern package for both legacy IPN and PayPal Webhooks, use [`sudiptpa/paypal-notifications`](https://github.com/sudiptpa/paypal-notifications).

Use this package when:

- you want a focused IPN-only package
- you need to modernize an existing IPN integration with minimal behavioral change

Use `paypal-notifications` when:

- you want support for both PayPal IPN and Webhooks
- you are building a newer integration around the modern PayPal notification model
- you want one package to handle legacy and newer notification flows together

## Quick Start

### Modern Fluent Usage

```php
use Sujip\PayPal\Notification\Events\Failure;
use Sujip\PayPal\Notification\Events\Invalid;
use Sujip\PayPal\Notification\Events\Verified;
use Sujip\PayPal\Notification\Ipn;

$result = Ipn::fromArray($_POST)
    ->sandbox()
    ->onVerified(function (Verified $event): void {
        $payload = $event->getPayload();

        // Process the verified PayPal IPN here.
    })
    ->onInvalid(function (Invalid $event): void {
        $payload = $event->getPayload();

        // Log the invalid payload here.
    })
    ->onError(function (Failure $event): void {
        $error = $event->error();

        // Log transport or verification errors here.
    })
    ->verify();
```

### Legacy Listener Usage

```php
use Sujip\PayPal\Notification\Events\Failure;
use Sujip\PayPal\Notification\Events\Invalid;
use Sujip\PayPal\Notification\Events\Verified;
use Sujip\PayPal\Notification\Handler\ArrayHandler;

$manager = (new ArrayHandler($_POST))
    ->sandbox()
    ->handle();

$manager->onVerified(function (Verified $event): void {
    $payload = $event->getPayload();

    // Process the verified PayPal IPN here.
});

$manager->onInvalid(function (Invalid $event): void {
    $payload = $event->getPayload();

    // Log the invalid payload here.
});

$manager->onError(function (Failure $event): void {
    $error = $event->error();

    // Log transport or verification errors here.
});

$manager->fire();
```

## Public API Stability

The package is intentionally conservative about the legacy integration shape. These areas should be treated as public API for consumers:

- `Sujip\PayPal\Notification\Ipn`
- `Sujip\PayPal\Notification\Handler\ArrayHandler`
- `Sujip\PayPal\Notification\Handler\StreamHandler`
- `Sujip\PayPal\Notification\Manager`
- verification events
- `Sujip\PayPal\Notification\Contracts\Service`
- listener methods and event names

Internal implementation classes may evolve over time, especially where compatibility wrappers exist to preserve user-facing behavior.

## Transport Resolution

The package resolves verification transports in this order:

1. a custom service passed via `->using()`
2. a transport or Guzzle client passed via `->withTransport()` or `->withClient()`
3. the built-in cURL transport when `ext-curl` is available
4. the optional Guzzle transport when Guzzle is installed

If none of those are available, the verification cycle fails with a clear transport exception.

## Backward Compatibility

The familiar listener-driven flow is intentionally preserved:

```php
$manager = (new ArrayHandler($payload))->sandbox()->handle();
$manager->onVerified(fn ($event) => null);
$manager->onInvalid(fn ($event) => null);
$manager->onError(fn ($event) => null);
$manager->fire();
```

There is also a modern fluent entry point with the same verification engine underneath:

```php
Ipn::fromArray($payload)
    ->sandbox()
    ->onVerified(fn ($event) => null)
    ->verify();
```

That means users can upgrade the package internals without needing a functionality rewrite in their applications, while newer integrations can adopt a cleaner API.

## Extendability

### Custom transport

Implement `Sujip\PayPal\Notification\Contracts\Service` and pass it to `->using()`.

### Optional Guzzle transport

Install Guzzle in your application and pass a client into `->withClient()`.

### External event dispatcher

Pass any compatible dispatcher object into `->withDispatcher()` as long as it provides `addListener()` and `dispatch()` methods.

## Support

- use the GitHub issue tracker for bugs and regressions
- report security issues privately using [SECURITY.md](SECURITY.md)
- use PayPal sandbox IPN verification in your own app before deploying changes

## Development

```bash
composer lint
composer stan
composer rector:check
composer test
composer test:coverage
```

## Testing Strategy

The test suite covers:

- endpoint switching between live and sandbox
- payload parsing and serialization
- verified, invalid, and failure event dispatching
- custom service injection
- local dispatcher behavior and external dispatcher interoperability
- legacy and modern public usage styles
- request transport validation behavior

## License

MIT
