# Architecture

## Goals

The package keeps the familiar legacy PayPal IPN listener flow while rewriting the internals around a dependency-light core:

- no hard dependency on Guzzle
- no hard dependency on Symfony Event Dispatcher
- framework-agnostic behavior
- custom transport and dispatcher injection
- stable public behavior for existing consumers

## Runtime Flow

1. A handler creates a payload source.
2. The handler resolves a verifier.
3. The verifier delegates the outbound verification request to a transport service.
4. The verification result is mapped into a `Verified`, `Invalid`, or `Failure` event.
5. The dispatcher notifies registered listeners.

## Main Components

### Handlers

- `ArrayHandler` accepts a payload array.
- `StreamHandler` reads `php://input`.
- `Handler` provides environment switching, dispatcher injection, and transport injection.

### Payloads

- `Payload` is the normalized payload value object.
- `Payload\Arrayable` and `Payload\Streamable` create payload instances for each runtime source.

### Verification

- `Http\Verifier` validates the PayPal response body.
- `Http\Request` resolves the transport strategy.
- `Http\CurlRequest` is the built-in transport when `ext-curl` is available.
- `Http\GuzzleRequest` supports optional Guzzle usage when users choose to install it.

### Events and Dispatching

- `Manager` is the orchestration entry point exposed to consumers.
- `Events\Verified`, `Events\Invalid`, and `Events\Failure` carry the result state.
- `EventDispatcher\EventDispatcher` is the package-local dispatcher implementation.
- `EventDispatcher\InteropEventDispatcher` lets consumers provide an external dispatcher object with `addListener()` and `dispatch()` methods.

## Extension Points

### Custom transport

Implement `Sujip\PayPal\Notification\Contracts\Service` and pass it through `->using()`.

### External dispatcher

Pass an object with `addListener()` and `dispatch()` methods via `->withDispatcher()`.

### Optional Guzzle support

Install `guzzlehttp/guzzle` and pass a Guzzle client via `->withClient()`.

## Compatibility Notes

The public listener model remains intentionally familiar:

```php
$manager = (new ArrayHandler($payload))->sandbox()->handle();
$manager->onVerified(fn ($event) => null);
$manager->onInvalid(fn ($event) => null);
$manager->onError(fn ($event) => null);
$manager->fire();
```

That compatibility goal is why the code favors small adapters over a larger API redesign.
