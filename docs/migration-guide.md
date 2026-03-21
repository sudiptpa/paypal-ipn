# Migration Guide

## From The Legacy Releases

The goal of this release line is behavioral continuity with a cleaner architecture.

What stays familiar:

- `ArrayHandler` and `StreamHandler`
- `sandbox()` and live mode behavior
- `handle()` returning the manager object
- `onVerified()`, `onInvalid()`, and `onError()` listeners
- `fire()` triggering the verification cycle

## What Changed Internally

- the package no longer hard-requires Guzzle
- the package no longer hard-requires Symfony Event Dispatcher
- the transport resolution is now explicit and extensible
- the dispatcher is now package-local by default
- the codebase now targets modern PHP `8.2` to `<8.6`
- a new `Sujip\PayPal\Notification\Ipn` facade offers a cleaner modern usage style

## Migration Examples

### Recommended modern usage

```php
Ipn::fromArray($payload)
    ->sandbox()
    ->onVerified($verifiedListener)
    ->onInvalid($invalidListener)
    ->onError($failureListener)
    ->verify();
```

This uses the same verifier and event model underneath, but with a modern fluent entry point.

### Existing legacy usage

```php
$manager = (new ArrayHandler($payload))->sandbox()->handle();
$manager->onVerified($verifiedListener);
$manager->onInvalid($invalidListener);
$manager->onError($failureListener);
$manager->fire();
```

This pattern still works and remains the compatibility path.

### Optional Guzzle integration

If you previously relied on Guzzle being installed transitively, install it directly in your project:

```bash
composer require guzzlehttp/guzzle
```

Then inject it explicitly:

```php
$manager = (new ArrayHandler($payload))
    ->withClient(new GuzzleHttp\Client())
    ->handle();

Ipn::fromArray($payload)
    ->withClient(new GuzzleHttp\Client())
    ->verify();
```

### External event dispatcher integration

If you want to continue using an external dispatcher object, pass it explicitly:

```php
$manager = (new ArrayHandler($payload))
    ->withDispatcher($dispatcher)
    ->handle();

Ipn::fromArray($payload)
    ->withDispatcher($dispatcher)
    ->onVerified($verifiedListener)
    ->verify();
```

## Recommended Upgrade Steps

1. Upgrade to PHP `8.2` or newer.
2. Require the new package version.
3. Keep your existing handler-based integration as-is, or adopt the new `Ipn` facade gradually.
4. Install optional transport dependencies only if your project wants them.
5. Run your integration tests against PayPal sandbox IPN payloads.
6. Keep your application-level payment checks in place.
