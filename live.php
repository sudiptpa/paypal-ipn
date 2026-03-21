<?php

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';

use Sujip\PayPal\Notification\Events\Failure;
use Sujip\PayPal\Notification\Events\Invalid;
use Sujip\PayPal\Notification\Events\Verified;
use Sujip\PayPal\Notification\Handler\ArrayHandler;
use Sujip\PayPal\Notification\Handler\StreamHandler;

$manager = (new StreamHandler())->handle();

$manager = (new ArrayHandler([
    'foo' => 'bar',
    'bar' => 'baz',
]))
    ->sandbox()
    ->handle();

$manager->onInvalid(function (Invalid $event): void {
    $payload = $event->getPayload();
    $error = $event->error();

    echo "Invalid\n";
});

$manager->onVerified(function (Verified $event): void {
    $payload = $event->getPayload();

    echo "Verified\n";
});

$manager->onError(function (Failure $event): void {
    $error = $event->error();

    echo "Error\n";
});

$manager->fire();
