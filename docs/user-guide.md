# User Guide

## Installation

```bash
composer require sudiptpa/paypal-ipn
```

Optional transport package:

```bash
composer require guzzlehttp/guzzle
```

## Modern Fluent Usage

```php
use Sujip\PayPal\Notification\Events\Failure;
use Sujip\PayPal\Notification\Events\Invalid;
use Sujip\PayPal\Notification\Events\Verified;
use Sujip\PayPal\Notification\Ipn;

$result = Ipn::fromArray($_POST)
    ->sandbox()
    ->onVerified(function (Verified $event): void {
        $payload = $event->getPayload();

        // Mark payment as verified.
    })
    ->onInvalid(function (Invalid $event): void {
        $payload = $event->getPayload();

        // Log suspicious or malformed IPN payloads.
    })
    ->onError(function (Failure $event): void {
        $message = $event->error();

        // Log connectivity or verification failures.
    })
    ->verify();
```

## Legacy Array Usage

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

    // Mark payment as verified.
});

$manager->onInvalid(function (Invalid $event): void {
    $payload = $event->getPayload();

    // Log suspicious or malformed IPN payloads.
});

$manager->onError(function (Failure $event): void {
    $message = $event->error();

    // Log connectivity or verification failures.
});

$manager->fire();
```

## Raw Payload Usage

```php
use Sujip\PayPal\Notification\Ipn;

$result = Ipn::fromRaw(file_get_contents('php://input') ?: '')
    ->verify();
```

## Input Stream Usage

```php
use Sujip\PayPal\Notification\Handler\StreamHandler;
use Sujip\PayPal\Notification\Ipn;

$legacyManager = (new StreamHandler())
    ->handle();

$modernResult = Ipn::fromStream()
    ->verify();
```

## Environments

Use live mode by default, or switch to the PayPal sandbox endpoint:

```php
$handler->sandbox();
$handler->live();

$ipn->sandbox();
$ipn->live();
```

## Custom Transport

```php
use Sujip\PayPal\Notification\Contracts\Service;
use Sujip\PayPal\Notification\Http\Response;
use Sujip\PayPal\Notification\Payload;

final class LaravelHttpTransport implements Service
{
    public function call(Payload $payload): Response
    {
        // Call your framework client here.
        return new Response('VERIFIED', 200);
    }
}
```

Attach it like this:

```php
$manager = (new ArrayHandler($_POST))
    ->using(new LaravelHttpTransport())
    ->handle();

$result = Ipn::fromArray($_POST)
    ->using(new LaravelHttpTransport())
    ->verify();
```

## External Dispatcher

```php
$manager = (new ArrayHandler($_POST))
    ->withDispatcher($yourDispatcher)
    ->handle();

$result = Ipn::fromArray($_POST)
    ->withDispatcher($yourDispatcher)
    ->onVerified($verifiedListener)
    ->verify();
```

Your dispatcher only needs two methods:

- `addListener(string $eventName, callable $listener): void`
- `dispatch(object $event, string $eventName): object`

## Event Names

- `ipn:verified`
- `ipn:invalid`
- `ipn:verification.failure`

## Recommended Backend Checks

Even after PayPal verification succeeds, keep your normal business checks:

- validate the receiver email or merchant ID
- validate the amount and currency
- validate the payment status
- ignore duplicate transaction IDs
- log invalid and failure events
