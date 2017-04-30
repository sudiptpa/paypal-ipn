# PayPal IPN

**PayPal Instant Payment Notification Listener driver for PHP**

[![StyleCI](https://styleci.io/repos/77828329/shield?style=flat&branch=master)](https://styleci.io/repos/77828329)
[![Build Status](https://travis-ci.org/sudiptpa/paypal-ipn.svg?branch=master)](https://travis-ci.org/sudiptpa/paypal-ipn)
[![Latest Stable Version](https://poser.pugx.org/sudiptpa/paypal-ipn/v/stable)](https://packagist.org/packages/sudiptpa/paypal-ipn)
[![Total Downloads](https://poser.pugx.org/sudiptpa/paypal-ipn/downloads)](https://packagist.org/packages/sudiptpa/paypal-ipn)
[![License](https://poser.pugx.org/sudiptpa/paypal-ipn/license)](https://packagist.org/packages/sudiptpa/paypal-ipn)

## Requirements
This package requires PHP >=5.5

## Installation

This package is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "sudiptpa/paypal-ipn": "~2.0"
    }
}
```
If you really need to work on guzzle 5.* pull version below.

```
{
    "require": {
        "sudiptpa/paypal-ipn": "1.0.x-dev",
    }
}
```
And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following are 2 different methods provided by the package to handle PayPal IPN.

### Using ArrayListener by passing array of Data
```php
  require __DIR__.'/vendor/autoload.php';

  use PayPal\IPN\Event\IPNInvalid;
  use PayPal\IPN\Event\IPNVerificationFailure;
  use PayPal\IPN\Event\IPNVerified;
  use PayPal\IPN\Listener\Http\ArrayListener;

  $listener = new ArrayListener;
  
  /*
   * Payload received from PayPal end.
   */
  $data = array(
      'foo' => 'bar',
      'bar' => 'baz',
  );

  $listener->setData($data);

  $listener = $listener->run();

  $listener->onInvalid(function (IPNInvalid $event) {
      $ipnMessage = $event->getMessage();

     // IPN message was was invalid, something is not right! Do your logging here...
  });

  $listener->onVerified(function (IPNVerified $event) {
      $ipnMessage = $event->getMessage();

      // IPN message was verified, everything is ok! Do your processing logic here...
  });

  $listener->onVerificationFailure(function (IPNVerificationFailure $event) {
      $error = $event->getError();

      // Something bad happend when trying to communicate with PayPal! Do your logging here...
  });

  $listener->listen();
```

### Using InputStreamListener

```php
  use PayPal\IPN\Event\IPNInvalid;
  use PayPal\IPN\Event\IPNVerificationFailure;
  use PayPal\IPN\Event\IPNVerified;
  use PayPal\IPN\Listener\Http\InputStreamListener;

  $listener = new InputStreamListener;

  $listener = $listener->run();

  $listener->onInvalid(function (IPNInvalid $event) {
      $ipnMessage = $event->getMessage();

      // IPN message was was invalid, something is not right! Do your logging here...
  });

  $listener->onVerified(function (IPNVerified $event) {
      $ipnMessage = $event->getMessage();

      // IPN message was verified, everything is ok! Do your processing logic here...
  });

  $listener->onVerificationFailure(function (IPNVerificationFailure $event) {
      $error = $event->getError();

      // Something bad happend when trying to communicate with PayPal! Do your logging here...
  });

  $listener->listen();
```

## Contributing

Contributions are **welcome** and will be fully **credited**.

Contributions can be made via a Pull Request on [Github](https://github.com/sudiptpa/paypal-ipn).

## Testing

PayPal provide an Instant Payment Notification (IPN) simulator here: [https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNSimulator/](https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNSimulator/)

## Support

If you are having general issues with the package, feel free to drop me and email [sudiptpa@gmail.com](mailto:sudiptpa@gmail.com)

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/sudiptpa/paypal-ipn/issues),
or better yet, fork the library and submit a pull request.
