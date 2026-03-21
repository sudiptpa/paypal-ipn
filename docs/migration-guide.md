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

## Migration Examples

### Existing usage

```php
$manager = (new ArrayHandler($payload))->sandbox()->handle();
$manager->onVerified($verifiedListener);
$manager->onInvalid($invalidListener);
$manager->onError($failureListener);
$manager->fire();
```

This pattern still works.

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
```

### External event dispatcher integration

If you want to continue using an external dispatcher object, pass it explicitly:

```php
$manager = (new ArrayHandler($payload))
    ->withDispatcher($dispatcher)
    ->handle();
```

## Recommended Upgrade Steps

1. Upgrade to PHP `8.2` or newer.
2. Require the new package version.
3. Install optional transport dependencies only if your project wants them.
4. Run your integration tests against PayPal sandbox IPN payloads.
5. Keep your application-level payment checks in place.
