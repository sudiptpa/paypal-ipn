# Guzzle Transport

Guzzle support is optional.

Install it in your application when you want it:

```bash
composer require guzzlehttp/guzzle
```

Then pass a client instance into the handler:

```php
use GuzzleHttp\Client;
use Sujip\PayPal\Notification\Handler\ArrayHandler;

$manager = (new ArrayHandler($_POST))
    ->withClient(new Client())
    ->handle();
```

This keeps the package itself free from a hard Guzzle dependency while still supporting teams that standardize on Guzzle.
