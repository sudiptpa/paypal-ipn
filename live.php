<?php

require __DIR__.'/vendor/autoload.php';

use Sujip\PayPal\Notification\Events\Failure;
use Sujip\PayPal\Notification\Events\Invalid;
use Sujip\PayPal\Notification\Events\Verified;
use Sujip\PayPal\Notification\Handler\ArrayHandler;
use Sujip\PayPal\Notification\Handler\StreamHandler;

// Usage of this package in two different ways

$event = (new StreamHandler())->handle();

// or

$event = (new ArrayHandler([
    'foo' => 'bar',
    'bar' => 'baz',
]))
    ->sandbox()
    ->handle();

$event->onInvalid(function (Invalid $request) {
    $error = $request->error();
    $payload = $request->getPayload();

    echo "Invalid \n";

    // Log error, payload was invalid, or something.
});

$event->onVerified(function (Verified $request) {
    $payload = $request->getPayload();

    echo "Verified \n";

    // Ok, payload was valid, go ahead with your app logic.
});

$event->onError(function (Failure $request) {
    $error = $request->error();

    echo "Error \n";

    // Oh snap !. error occured while establishing connection !
});

$event->fire();
