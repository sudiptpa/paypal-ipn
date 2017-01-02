<?php

require __DIR__.'/vendor/autoload.php';

use PayPal\IPN\Event\IPNInvalid;
use PayPal\IPN\Event\IPNVerificationFailure;
use PayPal\IPN\Event\IPNVerified;
use PayPal\IPN\Listener\Http\InputStreamListener;

$listener = new InputStreamListener();

$listener = $listener->run();

$listener->onInvalid(function (IPNInvalid $event) {
    $ipnMessage = $event->getMessage();

    file_put_contents('outcome.txt', "INVALID\n\n$ipnMessage");
});

$listener->onVerified(function (IPNVerified $event) {
    $ipnMessage = $event->getMessage();

    file_put_contents('outcome.txt', "VERIFIED\n\n$ipnMessage");
});

$listener->onVerificationFailure(function (IPNVerificationFailure $event) {
    $error = $event->getError();

    file_put_contents('outcome.txt', "VERIFICATION FAILURE\n\n$error");
});

$listener->listen();
